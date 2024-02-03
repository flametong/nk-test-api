<?php

namespace App\Helpers;

class Parser
{
    public static function getPatternFromRoute(string $route): string
    {
        $regexString = str_replace("{id}", "(\d+)", $route);
        $routePattern = "/^" . str_replace("/", "\/", $regexString) . "$/";

        return $routePattern;
    }

    public static function isRegex(string $pattern): bool
    {
        set_error_handler(function () {
        });

        preg_match($pattern, '');

        $error = preg_last_error();

        restore_error_handler();

        return $error === PREG_NO_ERROR;
    }

    public static function parseQueryParams(string $queryParams): array
    {
        $params = [];

        if ($queryParams !== '') {
            parse_str($queryParams, $params);
        }

        return $params;
    }
}
