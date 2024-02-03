<?php

namespace App\Services\Notifications\Sms;

use App\Interfaces\ISmsService;
use GuzzleHttp\Client;
use WebRegul\SmsRu\Message\SmsRuMessage;
use WebRegul\SmsRu\Message\To;
use WebRegul\SmsRu\SmsRuApi;
use WebRegul\SmsRu\SmsRuConfig;

class SmsRuService implements ISmsService
{
    public function sendSms(string $phoneNumber, string $message): void
    {
        $api = new SmsRuApi(
            new SmsRuConfig([
                'api_id' => $_ENV['SMS_RU_API_ID'],
                'test'   => 1,
                'json'   => 1,
            ]),
            new Client()
        );

        $api->send(new SmsRuMessage(new To($phoneNumber, $message)));
    }
}
