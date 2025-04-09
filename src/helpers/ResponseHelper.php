<?php

namespace Hp\MyApp\helpers;

class ResponseHelper
{
    public static bool $testMode = false;
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);

        if (!self::$testMode) {
            exit;
        }
    }
}
