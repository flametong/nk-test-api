<?php

namespace App\Services\Notifications\Sms;

use App\Interfaces\ISmsService;
use SmsAero\SmsAeroMessage;

class SmsAeroService implements ISmsService
{
    public function sendSms(string $phoneNumber, string $message): void
    {
        $smsAeroMessage = new SmsAeroMessage($_ENV['SMS_AERO_EMAIL'], $_ENV['SMS_AERO_API_KEY']);
        
        $smsAeroMessage->send([
            'number' => $phoneNumber,
            'text'   => $message,
            'sign'   => 'SMS Aero'
        ]);
    }
}
