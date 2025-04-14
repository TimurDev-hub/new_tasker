<?php declare(strict_types=1);

use Router\Router;

require_once __DIR__ . '/../../../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

$router = new Router;

require_once __DIR__ . '/../src/core/router/Routes.php';

try {
	if (strpos($uri, '/api') !== false && strpos($uri, '/api') === 0) $router->dispatchRoutes(uri: $uri, httpMethod: $httpMethod);
	else require_once __DIR__ . '/../../client/view/index.html';

} catch (\Throwable $exc) {
	require_once __DIR__ . '/../../client/view/index.html';
	echo json_encode([
		'reload' => false,
		'error' => $exc->getMessage()
	]);
}

echo '<pre>';
$router->dumpObject();