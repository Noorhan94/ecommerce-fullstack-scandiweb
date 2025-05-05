<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class ProductType extends ObjectType
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => function () {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn($product) => $product->getId()
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn($product) => $product->getName()
                    ],
                    'type' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn($product) => $product->getType()
                    ],
                    'price' => [
                        'type' => Type::float(),
                        'resolve' => fn($product) => $product->getPrice()
                    ],
                    'in_stock' => [
                        'type' => Type::nonNull(Type::boolean()),
                        'resolve' => fn($product) => $product->isInStock()
                    ],
                    'category' => [
                        'type' => Type::string(),
                        'resolve' => fn($product) => $product->getCategory()
                    ],
                    'gallery' => [
                        'type' => Type::listOf(Type::string()),
                        'resolve' => fn($product) => $product->getGallery()
                    ],
                    'display_attributes' => [
                        'type' => Type::listOf(Type::string()),
                        'resolve' => fn($product) => $product->getDisplayAttributes()
                    ],
                    'attributes' => [
                        'type' => Type::listOf(AttributeType::getInstance()),
                        'resolve' => fn($product) => $product->getAttributes()
                    ],
                    'description' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn($product) => $product->getDescription()
                    ],
                ];
            }
        ]);
    }
}
