<?php
declare(strict_types=1);

namespace App\GraphQL;

use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;


class SchemaFactory {
    public static function build(): Schema {
        return new Schema([
            'query' => new ObjectType([
                'name' => 'Query',
                'fields' => QueryResolver::getQueries() // ✅ Correct import
            ]),
            'mutation' => new ObjectType([
                'name' => 'Mutation',
                'fields' => MutationResolver::getMutations()
            ])
        ]);
    }

    
}
