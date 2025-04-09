<?php

use Dotenv\Dotenv;
use Hp\MyApp\routes\Routes;

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Set response type
header('Content-Type: application/json');

// Get request info
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Handle routes
Routes::handle($uri, $method);

