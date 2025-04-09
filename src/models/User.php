<?php
namespace Hp\MyApp\models;

use PDO;
use Hp\MyApp\Config\Database;

class User
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
    
    public function create(string $name, string $email, string $password): int
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
        ]);

        return (int) $this->db->lastInsertId();
    }
}

?>