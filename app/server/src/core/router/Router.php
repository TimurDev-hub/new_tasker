<?php declare(strict_types=1);

namespace Router;

use Utils\Logger;

final class Router
{
	private array $routes = [];

	public function setRoute(string $uri, string $httpMethod, array $request): void
	{
		$this->routes["$httpMethod $uri"] = [
			'handler' => new $request['handler'](),
			'method' => $request['method']
		];
	}

	public function dispatchRoutes(string $uri, string $httpMethod): void
	{
		$routeKey = "$httpMethod $uri";
	}

	public function dumpObject()
	{
		print_r($this);
	}
}