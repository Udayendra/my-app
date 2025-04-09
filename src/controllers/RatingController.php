<?php

namespace Hp\MyApp\controllers;

use Hp\MyApp\models\Rating;
use Hp\MyApp\helpers\ResponseHelper;

class RatingController
{
    public function rate(?array $mockData = null)
    {
        $data = $mockData ?? json_decode(file_get_contents('php://input'), true);

        if (!isset($data['recipe_id'], $data['rating'])) {
            return ResponseHelper::json(['error' => 'recipe_id and rating are required'], 400);
        }

        $ratingModel = new Rating();
        $ratingModel->addRating($data['recipe_id'], $data['rating']);

        return ResponseHelper::json(['message' => 'Rating added successfully'], 201);
    }

    public function getAverage($recipeId)
    {
        $ratingModel = new Rating();
        $average = $ratingModel->getAverageRating($recipeId);

        return ResponseHelper::json(['recipe_id' => $recipeId, 'average_rating' => $average], 200);
    }
}
