<?php
declare(strict_types=1);

namespace App\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderAttributeInputType {
    private static ?InputObjectType $instance = null;

    public static function getInstance(): InputObjectType {
        if (self::$instance === null) {
            self::$instance = new InputObjectType([
                'name' => 'OrderAttributeInput',
                'fields' => [
                    'key' => Type::nonNull(Type::string()),
                    'value' => Type::nonNull(Type::string()),
                ]
            ]);
        }

        return self::$instance;
    }
}
