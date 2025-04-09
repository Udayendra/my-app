<?php

namespace Hp\MyApp\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService
{
    private static string $secret;

    public static function init(): void
    {
        if (!isset(self::$secret)) {
            self::$secret = $_ENV['JWT_SECRET'];
        }
    }

    public static function generateToken(int|string $userId): string
    {
        self::init();

        $issuedAt = time();
        $expire = $issuedAt + (60 * 60); // Token valid for 1 hour

        $tokenPayload = [
            'sub' => $userId,
            'iat' => $issuedAt,
            'exp' => $expire
        ];

        return JWT::encode($tokenPayload, self::$secret, 'HS256');
    }

    public static function verifyToken(string $token): object|false
    {
        self::init();

        try {
            return JWT::decode($token, new Key(self::$secret, 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }
}
