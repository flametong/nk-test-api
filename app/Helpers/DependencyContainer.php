<?php

namespace App\Helpers;

use App\Interfaces\ISmsService;
use App\Services\Notifications\NotificationService;

class DependencyContainer
{
    private static $instance;
    private $notificationService;

    private function __construct(ISmsService $smsService)
    {
        $this->notificationService = new NotificationService($smsService);
    }

    public static function getInstance(ISmsService $smsService)
    {
        if (!self::$instance) {
            self::$instance = new DependencyContainer($smsService);
        }
        
        return self::$instance;
    }

    public function getNotificationService()
    {
        return $this->notificationService;
    }
}
