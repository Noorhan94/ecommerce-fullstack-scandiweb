<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

abstract class BaseType extends ObjectType
{
    protected static function commonFields(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::id())]
        ];
    }
    
}
