<?php declare(strict_types=1);

namespace Router;

use Controllers\{AuthController, TaskController, UserController};

Router::getInstance()->setRoute(httpMethod: 'POST', uri: '/api/auth', request: [
	Router::HANDLER => AuthController::class,
	Router::METHOD => 'create'
]);

Router::getInstance()->setRoute(httpMethod: 'POST', uri: '/api/task', request: [
	Router::HANDLER => TaskController::class,
	Router::METHOD => 'create'
]);

Router::getInstance()->setRoute(httpMethod: 'POST', uri: '/api/user', request: [
	Router::HANDLER => UserController::class,
	Router::METHOD => 'create'
]);

Router::getInstance()->setRoute(httpMethod: 'GET', uri: '/api/task/{id}', request: [
	Router::HANDLER => TaskController::class,
	Router::METHOD => 'index'
]);

Router::getInstance()->setRoute(httpMethod: 'DELETE', uri: '/api/auth/{id}', request: [
	Router::HANDLER => AuthController::class,
	Router::METHOD => 'delete'
]);

Router::getInstance()->setRoute(httpMethod: 'DELETE', uri: '/api/task/{user}/{task}', request: [
	Router::HANDLER => TaskController::class,
	Router::METHOD => 'delete'
]);

Router::getInstance()->setRoute(httpMethod: 'DELETE', uri: '/api/user/{id}', request: [
	Router::HANDLER => UserController::class,
	Router::METHOD => 'delete'
]);
