<?php

namespace App\Helpers;

class Debugger
{
    public static function dump($data, bool $die = false)
    {
        echo '<pre>' . print_r($data, 1) . '</pre>';

        if ($die) {
            die();
        }
    }
}
