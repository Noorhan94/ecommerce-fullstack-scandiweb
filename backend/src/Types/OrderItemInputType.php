<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderItemInputType extends InputObjectType {
    
    private static ?self $instance = null;

    public static function getInstance(): self {
        if (!self::$instance) {
            self::$instance = new self([
                'name' => 'OrderItemInput',
                'fields' => [
                    'product_id' => ['type' => Type::nonNull(Type::string())],
                    'quantity' => ['type' => Type::nonNull(Type::int())],
                    'price' => ['type' => Type::nonNull(Type::float())], // include if needed
                    'attributes' => [
                        'type' => Type::nonNull(Type::listOf(OrderAttributeInputType::getInstance()))
                    ]
                ]
            ]);
        }
        return self::$instance;
    }
}
