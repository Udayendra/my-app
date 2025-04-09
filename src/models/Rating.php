<?php

namespace Hp\MyApp\models;

use Hp\MyApp\config\Database;
use PDO;

class Rating
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function addRating(int $recipeId, int|float $rating): void
    {
        $stmt = $this->conn->prepare("INSERT INTO ratings (recipe_id, rating, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$recipeId, $rating]);
    }

    public function getAverageRating(int $recipeId): float
    {
        $stmt = $this->conn->prepare("SELECT AVG(rating) as avg_rating FROM ratings WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return round((float) $result['avg_rating'], 2);
    }
}
