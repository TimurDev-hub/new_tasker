<?php

use Router\Router;
use Controllers\{AuthController, TaskController, UserController};

$router = new Router;

$router->setRoute(uri: '/api/auth', httpMethod: 'POST', request: [
	'handler' => AuthController::class,
	'method' => 'create'
]);

$router->setRoute(uri: '/api/task', httpMethod: 'POST', request: [
	'handler' => TaskController::class,
	'method' => 'create'
]);

$router->setRoute(uri: '/api/user', httpMethod: 'POST', request: [
	'handler' => UserController::class,
	'method' => 'create'
]);

$router->setRoute(uri: '/api/task', httpMethod: 'GET', request: [
	'handler' => TaskController::class,
	'method' => 'index'
]);

$router->setRoute(uri: '/api/task/{id}', httpMethod: 'DELETE', request: [
	'handler' => TaskController::class,
	'method' => 'delete'
]);

$router->setRoute(uri: '/api/user/{id}', httpMethod: 'DELETE', request: [
	'handler' => AuthController::class,
	'method' => 'delete'
]);
