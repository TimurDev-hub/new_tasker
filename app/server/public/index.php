<?php declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use Router\Router;

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

$router = new Router();

try {
	require_once __DIR__ . '/../src/core/router/Routes.php';

	if (strpos($uri, '/api') !== false && strpos($uri, '/api') === 0) $router->dispatchRoutes(uri: $uri, httpMethod: $httpMethod);
	else require_once __DIR__ . '/../../client/view/index.html';

} catch (\Throwable $exc) {
	require_once __DIR__ . '/../../client/view/index.html';
	header("Content-Type: application/json");
	http_response_code(500);
	echo json_encode([
		'reload' => false,
		'error' => $exc->getMessage()
	]);
}