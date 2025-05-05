<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Order {
    public static function create(float $totalPrice, array $items): array {
        $pdo = Database::getInstance()->getConnection();

        // ✅ Ensure orders and orderitems tables exist before inserting
        self::ensureTablesExist($pdo);

        // ✅ Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (total_price) VALUES (:total_price)");
        $stmt->execute(['total_price' => $totalPrice]);
        $orderId = (int)$pdo->lastInsertId();

        foreach ($items as $item) {
            $attributes = is_string($item['attributes']) ? json_decode($item['attributes'], true) : $item['attributes'];

            $stmt = $pdo->prepare("
                INSERT INTO orderitems (order_id, product_id, quantity, attributes)
                VALUES (:order_id, :product_id, :quantity, :attributes)
            ");

            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'attributes' => json_encode($attributes),
            ]);
        }

        return [
            'id' => $orderId,
            'total_price' => $totalPrice,
            'items' => $items
        ];
    }

    private static function ensureTablesExist(PDO $pdo): void {
        // 🔒 Create orders table if it doesn't exist
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                total_price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // 🔒 Create orderitems table if it doesn't exist
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orderitems (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT,
                product_id VARCHAR(50),
                quantity INT NOT NULL,
                attributes JSON,
                FOREIGN KEY (order_id) REFERENCES orders(id)
            )
        ");
    }
}
