<?php
// Load Composer's autoloader for all dependencies
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Controller/GraphQL.php';


use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use App\Controller\GraphQL;

// Set content type for JSON responses
header('Content-Type: application/json; charset=UTF-8');

// Initialize FastRoute dispatcher with routes
$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('POST', '/graphql', [GraphQL::class, 'handle']);
    $r->addRoute('OPTIONS', '/graphql', [GraphQL::class, 'preflight']); // ✅ handle preflight
});

// Get HTTP method and URI path
$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET'; // Fallback to GET if unset
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH); // Extract path, default to '/'

// Strip base path dynamically (e.g., /ecommerce_testProject/public)
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = dirname($scriptName);
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

// Dispatch the request
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        // 404: Route not found
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        // 405: Method not allowed for this route
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;

    case Dispatcher::FOUND:
        // Route matched—call the handler
        $handler = $routeInfo[1]; // [class, method]
        $vars = $routeInfo[2]; // Route variables (none here)

        try {
            if (is_array($handler) && class_exists($handler[0]) && method_exists($handler[0], $handler[1])) {
                // Call the handler (GraphQL::handle)
                echo call_user_func([new $handler[0](), $handler[1]], $vars);
            } else {
                throw new Exception('Invalid route handler configuration');
            }
        } catch (Throwable $e) {
            // 500: Server error with details (hide in production)
            http_response_code(500);
            echo json_encode([
                'error' => 'Internal server error',
                'message' => $e->getMessage() // Remove in prod for security
            ]);
        }
        break;

    default:
        // Unexpected dispatch result
        http_response_code(500);
        echo json_encode(['error' => 'Routing error']);
        break;
}