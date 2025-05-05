<?php
declare(strict_types=1);

namespace App\Models\Category;

use App\Config\Database;

class Category
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function getAll(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
