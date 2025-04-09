<?php

namespace Hp\MyApp\models;

use Hp\MyApp\config\Database;
use PDO;

class Recipe
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function all(): array
    {
        $stmt = $this->conn->query("SELECT * FROM recipes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): array|false
    {
        $stmt = $this->conn->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $name, int $prep_time, int $difficulty, bool $vegetarian): int
    {
        $stmt = $this->conn->prepare("INSERT INTO recipes (name, prep_time, difficulty, vegetarian) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $prep_time, $difficulty, $vegetarian]);
        return (int) $this->conn->lastInsertId();
    }

    public function update(int $id, string $name, int $prep_time, int $difficulty, bool $vegetarian): bool
    {
        $stmt = $this->conn->prepare("UPDATE recipes SET name = ?, prep_time = ?, difficulty = ?, vegetarian = ? WHERE id = ?");
        return $stmt->execute([$name, $prep_time, $difficulty, $vegetarian, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM recipes WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function search(string $keyword): array
    {
        $stmt = $this->conn->prepare("
        SELECT * FROM recipes
        WHERE name LIKE :keyword
        OR difficulty LIKE :keyword
    ");
        $stmt->execute(['keyword' => "%$keyword%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
