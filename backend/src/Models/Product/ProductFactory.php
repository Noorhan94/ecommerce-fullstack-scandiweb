<?php
declare(strict_types=1);

namespace App\Models\Product;

class ProductFactory
{
    public static function create(array $data): ?Product
    {
        // Map category_id → name
        $categoryId = (int) ($data['category_id'] ?? 3); // default to tech
        $categoryName = self::mapCategoryIdToName($categoryId);
    
        // Inject it into the data array so product classes can use it
        $data['category'] = $categoryName;
    
        // Log for debug
        error_log("🛠 Assigned category '{$categoryName}' to product '{$data['name']}'");
    
        try {
            // Create product instance
            return match ($categoryName) {
                'tech' => new TechProduct($data),
                'clothes' => new ClothingProduct($data),
                default => new TechProduct($data)
            };
        } catch (\Throwable $e) {
            error_log("❌ ProductFactory error: " . $e->getMessage());
            return null;
        }
    }
    

    private static function mapCategoryIdToName(int $categoryId): string
    {
        return match ($categoryId) {
            2 => 'clothes',
            3 => 'tech',
            default => 'tech' // fallback if unknown
        };
    }
}
