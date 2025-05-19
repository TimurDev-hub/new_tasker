<?php declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use Router\Router;

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

try {
	require_once __DIR__ . '/../src/core/router/Routes.php';

	if (strpos($uri, '/api') !== false && strpos($uri, '/api') === 0) Router::getInstance()->dispatchRoutes(uri: $uri, httpMethod: $httpMethod);
	else require_once __DIR__ . '/../../client/view/index.html';

} catch (\Throwable) {
	require_once __DIR__ . '/../../client/view/index.html';
	http_response_code(500);
}