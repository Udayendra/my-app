<?php

use PHPUnit\Framework\TestCase;
use Hp\MyApp\controllers\AuthController;
use Hp\MyApp\helpers\ResponseHelper;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthControllerTest extends TestCase
{
    private AuthController $controller;

    protected function setUp(): void
    {
        ResponseHelper::$testMode = true;
        $this->controller = new AuthController();
    }

    public function testRegisterMissingFields()         // ----test1
    {
        ob_start();
        $this->controller->register(['name' => 'Ramu']); // missing email and password
        $output = ob_get_clean();
        
        // echo "\nRESPONSE OUTPUT: $output\n"; 
        $decoded = json_decode($output, true);
        $this->assertStringContainsString('Missing required fields', $decoded['error']);
    }

    public function testRegisterUserAlreadyExists()     // ----test2
    {
        $userData = [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password'
        ];

        // First registration
        ob_start();
        $this->controller->register($userData);
        ob_end_clean();

        // Second registration with same email
        ob_start();
        $this->controller->register($userData);
        $output = ob_get_clean();

        $decoded = json_decode($output, true);
        $this->assertEquals('User already exists', $decoded['error']);
    }


    public function testRegisterSuccess()               // ----test3
    {
        // Use a random email to avoid "User already exists" issue
        $email = 'test' . uniqid() . '@example.com';

        ob_start();
        $this->controller->register([
            'name' => 'Test User',
            'email' => $email,
            'password' => 'password'
        ]);
        $output = ob_get_clean();

        $decoded = json_decode($output, true);
        $this->assertArrayHasKey('token', $decoded);
        $this->assertNotEmpty($decoded['token']);
        $this->assertEquals('You have successfully registered', $decoded['message']);
    }

    public function testLoginMissingFields()            // ----test4
    {
        $email = 'test' . uniqid() . '@example.com';

        ob_start();
        $this->controller->login(['email' => $email]);
        $output = ob_get_clean();

        $decoded = json_decode($output, true);
        $this->assertEquals('Missing email or password', $decoded['error']);
    }

    public function testLoginInvalidCredential()        // ----test5
    {
        $email = 'test' . uniqid() . '@example.com';
        $password = 'password';

        // Register a user
        ob_start();
        $this->controller->register([
            'name' => 'Test User',
            'email' => $email,
            'password' => $password
        ]);
        ob_end_clean();

        // Log in with invalid credentials
        ob_start();
        $this->controller->login([
            'email' => 'nonexistent@example.com', // invalid email
            'password' => 'wrongpassword'         // invalid password
        ]);
        $output = ob_get_clean();
        $decoded = json_decode($output, true);
        $this->assertEquals('Invalid credentials', $decoded['error']);
    }

    public function testLogin()        // ----test5
    {
        $email = 'test' . uniqid() . '@example.com';
        $password = 'password';

        // Register a user
        ob_start();
        $this->controller->register([
            'name' => 'Test User',
            'email' => $email,
            'password' => $password
        ]);
        ob_end_clean();

        // Log in 
        ob_start();
        $this->controller->login([
            'email' => $email,
            'password' => $password
        ]);
        $output = ob_get_clean();

        $decoded = json_decode($output, true);
        $this->assertArrayHasKey('token', $decoded);
        $this->assertNotEmpty($decoded['token']);
        $this->assertEquals($email, $decoded['data']['email']);
    }
}
