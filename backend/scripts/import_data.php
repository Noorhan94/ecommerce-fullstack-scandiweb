<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

// Setup DB
$pdo = Database::getInstance()->getConnection();

// Read JSON file
$json = file_get_contents(__DIR__ . '/data.json');
$data = json_decode($json, true);

// Step 1: Get category name → ID mapping
$categoryStmt = $pdo->query("SELECT id, name FROM categories");
$categoryMap = [];
while ($row = $categoryStmt->fetch(PDO::FETCH_ASSOC)) {
    $categoryMap[strtolower($row['name'])] = $row['id'];
}

// Prepare insert statements
$insertProduct = $pdo->prepare("
    INSERT INTO products (id, name, in_stock, description, category_id, brand)
    VALUES (:id, :name, :in_stock, :description, :category_id, :brand)
");

$insertPrice = $pdo->prepare("
    INSERT INTO prices (product_id, amount, currency_label, currency_symbol)
    VALUES (:product_id, :amount, :currency_label, :currency_symbol)
");

$insertGallery = $pdo->prepare("
    INSERT INTO product_gallery (product_id, image_url)
    VALUES (:product_id, :image_url)
");

$insertAttribute = $pdo->prepare("
    INSERT INTO attributes (product_id, name, type)
    VALUES (:product_id, :name, :type)
");

$insertAttributeItem = $pdo->prepare("
    INSERT INTO attribute_items (id, attribute_id, display_value, value)
    VALUES (:id, :attribute_id, :display_value, :value)
");

// Step 2: Insert products
foreach ($data as $product) {
    $categoryName = strtolower($product['category']);
    $categoryId = $categoryMap[$categoryName] ?? null;

    if (!$categoryId) {
        echo "❌ Unknown category: {$product['category']}\n";
        continue;
    }

    $insertProduct->execute([
        ':id' => $product['id'],
        ':name' => $product['name'],
        ':in_stock' => $product['in_stock'],
        ':description' => $product['description'],
        ':category_id' => $categoryId,
        ':brand' => $product['brand']
    ]);
    echo "✅ Inserted product: {$product['name']}\n";

    // Step 3: Insert price
    $insertPrice->execute([
        ':product_id' => $product['id'],
        ':amount' => $product['prices'][0]['amount'],
        ':currency_label' => $product['prices'][0]['currency']['label'],
        ':currency_symbol' => $product['prices'][0]['currency']['symbol']
    ]);

    // Step 4: Insert gallery images
    foreach ($product['gallery'] as $imageUrl) {
        $insertGallery->execute([
            ':product_id' => $product['id'],
            ':image_url' => $imageUrl
        ]);
    }

    // Step 5: Insert attributes and attribute items
    foreach ($product['attributes'] as $attribute) {
        $insertAttribute->execute([
            ':product_id' => $product['id'],
            ':name' => $attribute['name'],
            ':type' => $attribute['type']
        ]);

        $attributeId = $pdo->lastInsertId();

        foreach ($attribute['items'] as $item) {
            $insertAttributeItem->execute([
                ':id' => $item['id'],
                ':attribute_id' => $attributeId,
                ':display_value' => $item['displayValue'],
                ':value' => $item['value']
            ]);
        }
    }
}

echo "\n🎉 Data import complete!\n";
