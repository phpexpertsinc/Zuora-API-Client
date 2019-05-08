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

namespace PHPExperts\ZuoraClient\Managers;

use Carbon\Carbon;
use PHPExperts\RESTSpeaker\RESTSpeaker;
use PHPExperts\ZuoraClient\ZuoraClient;

class Contact extends Manager
{
    public function fetch(string $contactId)
    {
        $response = $this->api->get('v1/object/contact/' . $contactId);

        return $response;
    }

    public function update(string $contactId, array $fields)
    {
        $response = $this->api->put('v1/accounts/' . $contactId, [
            'json' => $fields,
        ]);

        if (!$response instanceof \stdClass || !property_exists($response, 'success')) {
            throw new \RuntimeException('Updating the Account was unsuccessful: Malformed API response.');
        } elseif ($response->success !== true) {
            $gatherFailureReasons = function ($response): string {
                $messages = [];
                foreach ($response->reasons as $reason) {
                    $messages[] = $reason->message;
                }

                return implode("\n", $messages);
            };

            throw new \RuntimeException(
                'Updating the Account was unsuccessful: ' . $gatherFailureReasons($response)
            );
        }

        return $response;
    }

    public function destroy(string $zuoraGUID)
    {
        $response = $this->api->delete('v1/accounts/' . $zuoraGUID);
    }

    public function query(string $zosql)
    {
        $info = $this->api->post('v1/action/query', [
            'json' => [
                'queryString' => $zosql,
            ],
        ]);

        $accounts = $info->records;

        usort($accounts, function ($a, $b) {
            return Carbon::createFromDate($b->CreatedDate) > Carbon::createFromDate($a->CreatedDate);
        });

        return $accounts;
    }
}
