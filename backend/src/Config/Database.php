<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static ?self $instance = null;
    private PDO $connection;

    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=localhost;dbname=ecommerce_db;charset=utf8",
                "root", "", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): self {
        return self::$instance ??= new self();
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
