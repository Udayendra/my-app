<?php

namespace Hp\MyApp\controllers;

use Hp\MyApp\models\User;
use Hp\MyApp\services\JwtService;
use Hp\MyApp\helpers\ResponseHelper;

class AuthController
{
    public function register(?array $mockData = null): void
    {
        $data = $mockData ?? json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name'], $data['email'], $data['password'])) {
            ResponseHelper::json(['error' => 'Missing required fields'], 400);
            return;
        }

        $user = new User();
        if ($user->findByEmail($data['email'])) {
            ResponseHelper::json(['error' => 'User already exists'], 409);
            return;
        }

        $userId = $user->create($data['name'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT));

        $token = JwtService::generateToken($userId);
        ResponseHelper::json(['token' => $token, 'message' => 'You have successfully registered'], 201);
        return;
    }

    public function login(?array $mockData = null): void
    {
        $data = $mockData ?? json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email'], $data['password'])) {
            ResponseHelper::json(['error' => 'Missing email or password'], 400);
            return;
        }

        $user = new User();
        $existingUser = $user->findByEmail($data['email']);

        if (!$existingUser || !password_verify($data['password'], $existingUser['password'])) {
            ResponseHelper::json(['error' => 'Invalid credentials'], 401);
            return;
        }

        $token = JwtService::generateToken($existingUser['id']);
        ResponseHelper::json(['token' => $token, 'data' => $existingUser], 200);
        return;
    }
}
