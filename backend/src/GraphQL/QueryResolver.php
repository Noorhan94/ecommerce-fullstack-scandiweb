<?php
declare(strict_types=1);

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use App\Types\CategoryType;
use App\Types\ProductType;
use App\Models\Category\Category;
use App\Models\Product\ProductRepository;

class QueryResolver
{
    public static function getQueries(): array
    {
        return [

            // ✅ CATEGORY QUERY (now backed by DB)
            'categories' => [
                'type' => Type::listOf(CategoryType::getInstance()),
                'resolve' => function () {
                    return Category::getAll();
                }
            ],

            // ✅ PRODUCTS QUERY
           'products' => [
                'type' => Type::listOf(ProductType::getInstance()),
                'resolve' => function () {
                    return ProductRepository::getAll();
                }
            ],
            'gallery' => [
                'type' => Type::listOf(Type::string()),
                'resolve' => fn($product) => $product->getGallery()
            ],
       
            // ✅ Single product by ID
            'product' => [
                'type' => ProductType::getInstance(),
                'args' => [
                    'id' => Type::nonNull(Type::string())
                ],
                'resolve' => function ($root, array $args) {
                    return ProductRepository::findById($args['id']);
                }
            ],



            // 🔁 You can add productsByCategory etc. here
        ];
    }
}
