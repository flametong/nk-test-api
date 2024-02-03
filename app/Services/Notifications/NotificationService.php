<?php

namespace App\Services\Notifications;

use App\Interfaces\ISmsService;

class NotificationService
{
    private $smsService;

    public function __construct(ISmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendNotification($phoneNumber, $message)
    {
        $this->smsService->sendSms($phoneNumber, $message);
    }
}
