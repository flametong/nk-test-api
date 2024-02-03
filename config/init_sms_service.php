<?php

use App\Services\Notifications\Sms\SmsAeroService;
use App\Services\Notifications\Sms\SmsRuService;

$providers = require_once CONFIG . '/providers.php';

$smsService = match ($providers['sms_provider']) {
    'smsru'   => new SmsRuService(),
    'smsaero' => new SmsAeroService(),
};
