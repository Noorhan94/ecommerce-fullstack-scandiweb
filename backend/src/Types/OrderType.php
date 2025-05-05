<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Types\OrderItemType;

class OrderType extends ObjectType {
    private static ?self $instance = null;

    public static function getInstance(): self {
        if (!self::$instance) {
            self::$instance = new self([
                'name' => 'Order',
                'fields' => [
                    'id' => ['type' => Type::nonNull(Type::int())],
                    'total_price' => ['type' => Type::nonNull(Type::float())],
                    'items' => [
                        'type' => Type::listOf(OrderItemType::getInstance()),
                        'resolve' => function ($order) {
                            return array_map(function ($item) {
                                if (isset($item['attributes']) && is_string($item['attributes'])) {
                                    $item['attributes'] = json_decode($item['attributes'], true);
                                }

                                return $item;
                            }, $order['items']);
                        }
                    ]
                ]
            ]);
        }
        return self::$instance;
    }
}
