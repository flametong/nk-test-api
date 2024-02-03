<?php

namespace App\Interfaces;

interface ISmsService
{
    public function sendSms(string $phoneNumber, string $message): void;
}
