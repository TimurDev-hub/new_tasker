<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Router\Router;
use Controllers\{AuthController, TaskController, UserController};

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

$router = new Router;

$router->setRoute(uri: '/api/auth', httpMethod: 'POST', request: [AuthController::class, 'create']);
$router->setRoute(uri: '/api/task', httpMethod: 'POST', request: [TaskController::class, 'create']);
$router->setRoute(uri: '/api/user', httpMethod: 'POST', request: [UserController::class, 'create']);
$router->setRoute(uri: '/api/task', httpMethod: 'GET', request: [TaskController::class, 'index']);
$router->setRoute(uri: '/api/task/{id}', httpMethod: 'DELETE', request: [TaskController::class, 'delete']);
$router->setRoute(uri: '/api/user/{id}', httpMethod: 'DELETE', request: [AuthController::class, 'delete']);

try {
	if (strpos($uri, '/api') !== false && strpos($uri, '/api') === 0) $router->dispatchRoutes(uri: $uri, httpMethod: $httpMethod);
	else require_once __DIR__ . '/../../client/view/index.html';

} catch (\Throwable) {
	http_response_code(404);
	require_once __DIR__ . '/../../client/view/index.html';
}