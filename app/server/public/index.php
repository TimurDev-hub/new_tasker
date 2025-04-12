<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/../src/core/router/Api.php';

try {
	if (strpos($uri, '/api') !== false && strpos($uri, '/api') === 0) $router->dispatchRoutes(uri: $uri, httpMethod: $httpMethod);
	else require_once __DIR__ . '/../../client/view/index.html';

} catch (\Throwable) {
	http_response_code(404);
	require_once __DIR__ . '/../../client/view/index.html';
}

echo '<pre>';
$router->dumpObject();