<?php
declare(strict_types=1);

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use App\Types\OrderType;
use App\Types\OrderItemInputType;
use App\Models\Order;

class MutationResolver {
    public static function getMutations(): array {
        return [
            'createOrder' => [
                'type' => OrderType::getInstance(),
                'args' => [
                    'total_price' => ['type' => Type::nonNull(Type::float())],
                    'items' => ['type' => Type::nonNull(Type::listOf(OrderItemInputType::getInstance()))]
                ],
                'resolve' => function($root, $args) {
                    // 🛑 DEBUG: Print received input from GraphQL
                    error_log("GraphQL Received: " . print_r($args, true));

                    return Order::create($args['total_price'], $args['items']);
                }
            ]
        ];
    }
}
