<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

// The attributes returned to the client in a query
class OrderAttributeType extends ObjectType
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self([
            'name' => 'OrderAttribute',
            'fields' => [
                'key' => ['type' => Type::nonNull(Type::string())],
                'value' => ['type' => Type::nonNull(Type::string())],
            ]
        ]);
    }
}
