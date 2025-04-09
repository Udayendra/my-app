<?php

namespace Hp\MyApp\middleware;

use Hp\MyApp\services\JwtService;
use Hp\MyApp\helpers\ResponseHelper;

class AuthMiddleware
{
    public static function handle(): int|false
    {
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            ResponseHelper::json(['error' => 'Unauthorized'], 401);
            return false;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $decoded = JwtService::verifyToken($token);

        if (!$decoded) {
            ResponseHelper::json(['error' => 'Invalid or expired token'], 401);
            return false;
        }

        return $decoded->sub; // return user ID
    }
}
