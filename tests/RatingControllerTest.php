<?php

namespace Hp\MyApp\tests;

use Hp\MyApp\controllers\RecipeController;
use Hp\MyApp\controllers\RatingController;
use Hp\MyApp\helpers\ResponseHelper;
use PHPUnit\Framework\TestCase;

class RatingControllerTest extends TestCase
{
    private RatingController $controller;
    private RecipeController $recipeController;
    private int $recipeId;

    protected function setUp(): void
    {
        ResponseHelper::$testMode = true;
        $this->controller = new RatingController();
        $this->recipeController = new RecipeController();

        // Creating a recipe to rate
        ob_start();
        $this->recipeController->store([
            'name' => 'Test Recipe',
            'prep_time' => 20,
            'difficulty' => 2,
            'vegetarian' => true,
        ]);
        $output = ob_get_clean();
        $data = json_decode($output, true);
        $this->recipeId = $data['id'];
    }

    public function testAddRatingSuccessfully()     // ----test1
    {
        ob_start();
        $this->controller->rate([
            'recipe_id' => $this->recipeId,
            'rating' => 4,
        ]);
        $output = ob_get_clean();
        $data = json_decode($output, true);

        $this->assertEquals('Rating added successfully', $data['message']);
    }

    public function testAddRatingMissingFields()    // ----test2
    {
        ob_start();
        $this->controller->rate([
            'recipe_id' => $this->recipeId
        ]);
        $output = ob_get_clean();
        $data = json_decode($output, true);

        $this->assertEquals('recipe_id and rating are required', $data['error']);
    }

    public function testGetAverageRating()          // ----test3
    {
        // Adding second rating to get an average
        $this->controller->rate([
            'recipe_id' => $this->recipeId,
            'rating' => 3,
        ]);

        ob_start();
        $this->controller->getAverage($this->recipeId);
        $output = ob_get_clean();
        $data = json_decode($output, true);

        $this->assertEquals($this->recipeId, $data['recipe_id']);
    }
}
