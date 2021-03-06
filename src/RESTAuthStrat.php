<?php declare(strict_types=1);

/**
 * This file is part of the Zuora PHP API Client, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/Zuora-API-Client
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\ZuoraClient;

use GuzzleHttp\Client as Guzzle_Client;
use Illuminate\Log\Logger;
use LogicException;
use PHPExperts\RESTSpeaker\RESTAuth as BaseRESTAuth;
use PHPExperts\ZuoraClient\Exceptions\ZuoraAPIException;

class RESTAuthStrat extends BaseRESTAuth
{
    /** @var string */
    protected $authMode;

    public function __construct(string $authStratMode)
    {
        if (!in_array($authStratMode, [self::AUTH_MODE_PASSKEY, self::AUTH_MODE_OAUTH2])) {
            throw new LogicException('Invalid Zuora REST auth mode.');
        }

        parent::__construct($authStratMode);
    }

    /**
     * @throws LogicException if token auth is attempted on an unsupported Zuora environment.
     * @throws ZuoraAPIException if an OAuth2 Token could not be successfully generated.
     * @return array The appropriate headers for OAuth2 Tokens.
     */
    protected function generateOAuth2TokenOptions(): array
    {
        /** @var string|null $accessToken */
        static $accessToken = null;

        $returnOauth2Token = function (string $accessToken): array {
            return [
                // Stop Guzzle from throwing exceptions on simple HTTP errors.
                'http_errors' => false,
                'headers' => [
                    'Authorization' => "bearer {$accessToken}",
                    'zuora-version' => ZuoraClient::ZUORA_API_VERSION,
                ],
            ];
        };

        if ($accessToken !== null) {
            return $returnOauth2Token($accessToken);
        }

        if ($this->authMode === self::AUTH_MODE_PASSKEY) {
            throw new LogicException('OAuth2 Tokens are not supported by Zuora\'s Production Copy env.');
        }

        $response = (new Guzzle_Client())->post(env('ZUORA_API_HOST') . 'oauth/token', [
            'headers' => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'zuora-version' => ZuoraClient::ZUORA_API_VERSION,
            ],
            'form_params' => [
                'client_id'     => env('ZUORA_API_CLIENT_ID'),
                'client_secret' => env("ZUORA_API_SECRET"),
                'grant_type'    => 'client_credentials',
            ],
        ]);
        $response = json_decode($response->getBody()->getContents());

        if (!$response || empty($response->access_token)) {
            throw new ZuoraAPIException('Could not generate an OAuth2 Token');
        }

        $accessToken = $response->access_token;

        return $returnOauth2Token($accessToken);
    }

    /**
     * @throws LogicException if the Zuora Rest Client is not configured in the .env file.
     * @return array The appropriate headers for passkey authorization.
     */
    protected function generatePasskeyOptions(): array
    {
        /** @security Do NOT remove this code. */
        if (env('ZUORA_API_AUTHMODE') === 'token' && (empty(env('ZUORA_API_USERNAME')) || empty(env('ZUORA_API_PASSWORD')))) {
            throw new LogicException('The Zuora Rest Client is not configured in the .env file.');
        }

        return [
            // Stop Guzzle from throwing exceptions on simple HTTP errors.
            'http_errors' => false,
            'headers' => [
                'zuora-version'      => ZuoraClient::ZUORA_API_VERSION,
                'apiAccessKeyId'     => env('ZUORA_API_USERNAME'),
                'apiSecretAccessKey' => env('ZUORA_API_PASSWORD'),
            ]
        ];
    }
}
