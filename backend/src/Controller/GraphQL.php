<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../GraphQL/SchemaFactory.php';

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Error\DebugFlag;
use App\GraphQL\SchemaFactory;

class GraphQL {
    public static function handle() {
        try {
            $schema = SchemaFactory::build();

            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $query = $input['query'] ?? '';
            $variables = $input['variables'] ?? null;
            $result = \GraphQL\GraphQL::executeQuery($schema, $query, [], null, $variables);
            $output = $result->toArray(\GraphQL\Error\DebugFlag::INCLUDE_DEBUG_MESSAGE | \GraphQL\Error\DebugFlag::INCLUDE_TRACE);
        } catch (\Throwable $e) {
            $output = [
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ];
        }
    
        header('Content-Type: application/json');
        echo json_encode($output, JSON_PRETTY_PRINT);
    }
    public static function preflight()
    {
        // Preflight response with CORS headers
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 86400'); // 1 day
        http_response_code(204); // No content
        exit;
    }

    }
