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

namespace PHPExperts\ZuoraClient\Tests\Integration\Managers;

use PHPExperts\ZuoraClient\RESTAuthStrat as RESTAuth;
use PHPExperts\ZuoraClient\Tests\TestCase;
use PHPExperts\ZuoraClient\ZuoraClient;

class AccountTest extends TestCase
{
    /** @var ZuoraClient */
    protected $api;

    public function setUp(): void
    {
        parent::setUp();

        $restAuth = new RESTAuth(RESTAuth::AUTH_MODE_PASSKEY);

        $this->api = new ZuoraClient($restAuth, env('ZUORA_API_HOST'));
        $restAuth->setApiClient($this->api->getApiClient());
    }

    public function testCanFetchAccountDetails()
    {
        $zuoraGUID = '8a80aba7693a825401695a4a53663134';
        $response = $this->api->account->fetch($zuoraGUID);
        dd($response);
    }

    public function testCanUpdateAccountDetails()
    {

    }
}
