<?php

namespace App\Helpers;

class RequestHandler
{
    public static function getRawBody(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public static function doResponse(string $status, array|string $message, int $code = 200): void
    {
        header('Content-Type: application/json');
        echo json_encode(
            [
                'status'  => $status,
                'message' => $message,
                'code'    => $code,
            ],
            JSON_PRETTY_PRINT
        );
    }
}
