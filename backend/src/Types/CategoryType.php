<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self([
            'name' => 'Category',
            'fields' => [
                'name' => Type::nonNull(Type::string())
            ]
        ]);
    }
}
