<?php

namespace Hp\MyApp\config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/config.php';

            try {
                self::$connection = new PDO(
                    "mysql:host={$config['db']['host']};dbname={$config['db']['database']};port={$config['db']['port']}",
                    $config['db']['user'],
                    $config['db']['password']
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Production apps should log this instead
                die("Database connection error: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
