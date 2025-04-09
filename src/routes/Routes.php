<?php

namespace Hp\MyApp\routes;

use Hp\MyApp\controllers\AuthController;
use Hp\MyApp\controllers\RecipeController;
use Hp\MyApp\controllers\RatingController;
use Hp\MyApp\middleware\AuthMiddleware;

class Routes
{
    public static function handle(string $uri, string $method): void
    {
        $authController = new AuthController();
        $recipeController = new RecipeController();
        $ratingController = new RatingController();

        // AUTH ROUTES
        if ($uri === '/register' && $method === 'POST') {
            $authController->register();
            return;
        }

        if ($uri === '/login' && $method === 'POST') {
            $authController->login();
            return;
        }

        // RECIPE ROUTES
        if ($uri === '/recipes' && $method === 'GET') {
            $recipeController->index();
            return;
        }

        if (preg_match('#^/recipes/(\d+)$#', $uri, $matches) && $method === 'GET') {
            $recipeController->show($matches[1]);
            return;
        }

        if ($uri === '/recipes' && $method === 'POST') {
            AuthMiddleware::handle();
            $recipeController->store();
            return;
        }

        if (preg_match('#^/recipes/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            AuthMiddleware::handle();
            $recipeController->update($matches[1]);
            return;
        }

        if (preg_match('#^/recipes/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
            AuthMiddleware::handle();
            $recipeController->destroy($matches[1]);
            return;
        }

        if ($uri === '/recipes/search' && $method === 'GET') {
            $recipeController->search();
            return;
        }

        // RATING ROUTES
        if ($uri === '/ratings' && $method === 'POST') {
            AuthMiddleware::handle();
            $ratingController->rate();
            return;
        }

        if (preg_match('#^/recipes/(\d+)/ratings$#', $uri, $matches) && $method === 'GET') {
            $ratingController->getAverage($matches[1]);
            return;
        }

        if ($uri === '/') {
            echo "Eat healthy, Be healthy ";
            return;
        }
        // 404 for unmatched routes
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}
