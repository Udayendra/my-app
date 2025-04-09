<?php

namespace Hp\MyApp\controllers;

use Hp\MyApp\models\Recipe;
use Hp\MyApp\helpers\ResponseHelper;

class RecipeController
{
    private Recipe $recipe;

    public function __construct()
    {
        $this->recipe = new Recipe();
    }

    public function index()
    {
        $recipes = $this->recipe->all();
        return ResponseHelper::json($recipes);
    }

    public function show(int $id)
    {
        $data = $this->recipe->find($id);

        if (!$data) {
            return ResponseHelper::json(['error' => 'Recipe not found'], 404);
        }

        return ResponseHelper::json($data);
    }

    public function store(?array $mockData = null)
    {
        $data = $mockData ?? json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name'], $data['prep_time'], $data['difficulty'], $data['vegetarian'])) {
            return ResponseHelper::json(['error' => 'Missing required fields'], 400);
        }

        $id = $this->recipe->create(
            $data['name'],
            (int) $data['prep_time'],
            (int) $data['difficulty'],
            (bool) $data['vegetarian']
        );

        return ResponseHelper::json(['message' => 'Recipe created', 'id' => $id], 201);
    }

    public function update(int $id, ?array $mockData = null)
    {
        $data = $mockData ?? json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name'], $data['prep_time'], $data['difficulty'], $data['vegetarian'])) {
            return ResponseHelper::json(['error' => 'Missing required fields'], 400);
        }

        $updated = $this->recipe->update(
            $id,
            $data['name'],
            (int) $data['prep_time'],
            (int) $data['difficulty'],
            (bool) $data['vegetarian']
        );

        if (!$updated) {
            return ResponseHelper::json(['error' => 'Failed to update'], 500);
        }

        return ResponseHelper::json(['message' => 'Recipe updated']);
    }

    public function destroy(int $id)
    {
        $deleted = $this->recipe->delete($id);

        if (!$deleted) {
            return ResponseHelper::json(['error' => 'Failed to delete'], 500);
        }

        return ResponseHelper::json(['message' => 'Recipe deleted']);
    }

    public function search()
    {
        $keyword = $_GET['q'] ?? '';

        if (!$keyword) {
            return ResponseHelper::json(['error' => 'Missing search keyword'], 400);
        }

        $results = $this->recipe->search($keyword);

        return ResponseHelper::json($results);
    }
}
