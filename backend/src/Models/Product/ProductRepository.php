<?php
declare(strict_types=1);

namespace App\Models\Product;

use App\Config\Database;
use App\Models\Attribute\AttributeFactory;

class ProductRepository
{
    private static array $products = [];

    public static function getAll(): array
    {
        if (!empty(self::$products)) {
            return array_values(self::$products);
        }

        $pdo = Database::getInstance()->getConnection();

        // Pull base product data + price
        $sql = "
            SELECT 
                p.*, 
                pr.amount AS price, 
                pr.currency_label, 
                pr.currency_symbol 
            FROM products p
            LEFT JOIN prices pr ON p.id = pr.product_id
        ";

        $stmt = $pdo->query($sql);
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($products as $row) {
            // Add gallery
            $row['gallery'] = self::getProductGallery($row['id']);

            // Create product object
            $product = ProductFactory::create($row);

            if (!$product || empty($product->getId())) {
                continue; // Skip invalid ones
            }

            // Fetch and assign attributes
            $attributes = self::getProductAttributes($product->getId());
            $product->setAttributes($attributes);

            // Save product indexed by ID
            self::$products[$product->getId()] = $product;
        }

        return array_values(self::$products);
    }

    public static function findById(string $id): ?Product
    {
        if (empty(self::$products)) {
            self::getAll(); // Load if not loaded yet
        }

        return self::$products[$id] ?? null;
    }

    private static function getProductGallery(string $productId): array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT image_url FROM product_gallery WHERE product_id = :product_id");
        $stmt->execute(['product_id' => $productId]);
        $rows = $stmt->fetchAll();

        return array_column($rows, 'image_url');
    }

    private static function getProductAttributes(string $productId): array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT id, name, type FROM attributes WHERE product_id = ?");
        $stmt->execute([$productId]);

        $attributes = [];

        while ($attr = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $itemStmt = $pdo->prepare("SELECT display_value FROM attribute_items WHERE attribute_id = ?");
            $itemStmt->execute([$attr['id']]);
            $items = array_column($itemStmt->fetchAll(), 'display_value');

            $attrData = [
                'name' => $attr['name'],
                'type' => $attr['type'],
                'items' => $items
            ];

            try {
                $attributes[] = AttributeFactory::create($attrData);
            } catch (\Throwable $e) {
                error_log("⚠️ Skipping attribute due to error: " . $e->getMessage());
            }
        }

        return $attributes;
    }
}
