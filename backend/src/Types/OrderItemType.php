<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderItemType extends ObjectType {
    private static ?self $instance = null;

    public static function getInstance(): self {
        return self::$instance ??= new self([
            'name' => 'OrderItem',
            'fields' => [
                'product_id' => ['type' => Type::nonNull(Type::string())],
                'quantity' => ['type' => Type::nonNull(Type::int())],
                'price' => ['type' => Type::nonNull(Type::float())],
                'attributes' => [
                    'type' => Type::listOf(OrderAttributeType::getInstance()),
                    'resolve' => function ($item) {
                        $attributes = is_string($item['attributes'])
                            ? json_decode($item['attributes'], true)
                            : $item['attributes'];

                        $formatted = [];
                        foreach ($attributes as $attr) {
                            $formatted[] = [
                                'key' => $attr['key'],
                                'value' => (string) $attr['value'],
                            ];
                        }

                        return $formatted;
                    }
                ]


            ]
        ]);
    }
}
