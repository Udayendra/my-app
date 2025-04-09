<?php

use PHPUnit\Framework\TestCase;
use Hp\MyApp\controllers\RecipeController;
use Hp\MyApp\helpers\ResponseHelper;

require_once __DIR__ . '/../vendor/autoload.php';

class RecipeControllerTest extends TestCase
{
    private RecipeController $controller;

    protected function setUp(): void
    {
        ResponseHelper::$testMode = true;
        $this->controller = new RecipeController();
    }

    public function testStoreRecipe()       // ----test1
    {
        ob_start();
        $this->controller->store([
            'name' => 'Chana Masala',
            'prep_time' => 30,
            'difficulty' => 2,
            'vegetarian' => true
        ]);
        $output = ob_get_clean();

        $decoded = json_decode($output, true);
        $this->assertEquals('Recipe created', $decoded['message']);
        $this->assertArrayHasKey('id', $decoded);
    }

    public function testIndex()             // ----test2
    {
        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $recipes = json_decode($output, true);
        $this->assertIsArray($recipes);
    }

    public function testShowRecipe()        // ----test3
    {
        // Create a recipe first
        ob_start();
        $this->controller->store([
            'name' => 'Paneer Tikka',
            'prep_time' => 25,
            'difficulty' => 3,
            'vegetarian' => true
        ]);
        $output = ob_get_clean();
        $decoded = json_decode($output, true);
        $id = $decoded['id'];

        ob_start();
        $this->controller->show($id);
        $showOutput = ob_get_clean();
        $recipe = json_decode($showOutput, true);

        $this->assertEquals('Paneer Tikka', $recipe['name']);
    }

    public function testUpdateRecipe()      // ----test4
    {
        // First create a recipe
        ob_start();
        $this->controller->store([
            'name' => 'Dal Tadka',
            'prep_time' => 20,
            'difficulty' => 1,
            'vegetarian' => true
        ]);
        $output = ob_get_clean();
        $decoded = json_decode($output, true);
        $id = $decoded['id'];

        ob_start();
        $this->controller->update($id, [
            'name' => 'Dal Fry',
            'prep_time' => 15,
            'difficulty' => 1,
            'vegetarian' => true
        ]);
        $updateOutput = ob_get_clean();
        $result = json_decode($updateOutput, true);

        $this->assertEquals('Recipe updated', $result['message']);
    }

    public function testDestroyRecipe()     // ----test5
    {
        // Create a recipe first
        ob_start();
        $this->controller->store([
            'name' => 'Aloo Gobi',
            'prep_time' => 35,
            'difficulty' => 2,
            'vegetarian' => true
        ]);
        $output = ob_get_clean();
        $decoded = json_decode($output, true);
        $id = $decoded['id'];

        ob_start();
        $this->controller->destroy($id);
        $deleteOutput = ob_get_clean();
        $result = json_decode($deleteOutput, true);

        $this->assertEquals('Recipe deleted', $result['message']);
    }

    public function testSearchRecipe()      // ----test6
    {
        // Insert something to find
        ob_start();
        $this->controller->store([
            'name' => 'Veg Pulao',
            'prep_time' => 20,
            'difficulty' => 2,
            'vegetarian' => true
        ]);
        ob_end_clean();

        $_GET['q'] = 'Pulao';

        ob_start();
        $this->controller->search();
        $searchOutput = ob_get_clean();

        $results = json_decode($searchOutput, true);
        $this->assertNotEmpty($results);
        $this->assertStringContainsString('Pulao', $results[0]['name']);
    }
}
