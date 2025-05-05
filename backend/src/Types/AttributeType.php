<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class AttributeType extends ObjectType
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => function () {
                return [
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn($attr) => $attr->getName()
                    ],
                    'type' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn($attr) => $attr->getType()
                    ],
                    'items' => [
                        'type' => Type::listOf(Type::string()),
                        'resolve' => fn($attr) => $attr->getItems()
                    ]
                ];
            }
        ]);
    }
}
