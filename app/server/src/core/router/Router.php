<?php declare(strict_types=1);

namespace Router;

use Utils\Logger;
use Controllers\BaseController;

final class Router
{
	public const HANDLER = 'handler'; // Константы много-где будут к месту;
	public const METHOD = 'method';

	private array $routes = [];

	public function setRoute(string $httpMethod, string $uri, array $request): void
	{
		[self::HANDLER => $handlerClass, self::METHOD => $method] = $request; // Деструктуризация ассоциативного массива;

		if (!class_exists($handlerClass)) throw new \InvalidArgumentException("Controller '$handlerClass' doesn`t exist!");

		$this->routes["$httpMethod $uri"] = [
			self::HANDLER => new $handlerClass(), // При создании объектов лучше всегда указывать скобки;
			self::METHOD => $method
		];
	}

	private function parseUri(string $uri): string
	{
		return parse_url(trim($uri), PHP_URL_PATH);
	}

	public function dispatchRoutes(string $httpMethod, string $uri): void
	{
		header("Content-Type: application/json");

		try {
			$path = $this->parseUri(uri: $uri);
			$routeKey = "$httpMethod $path";

			if (!isset($this->routes[$routeKey])) throw new \InvalidArgumentException("Route '$routeKey' doesn`t exist!");

			[self::HANDLER => $controller, self::METHOD => $method] = $this->routes[$routeKey]; // Деструктуризация ассоциативного массива;

			if (!$controller instanceof BaseController) throw new \InvalidArgumentException("Controller '$controller' doesn`t correct!");
			if (!method_exists($controller, $method)) throw new \InvalidArgumentException("Method '$method' doesn`t exist in controller '$controller'!");

			echo $controller->$method($path);

		} catch (\Exception $exc) {
			Logger::handleError(exc: $exc, file: Logger::ROUTER_FILE);
			http_response_code(404);
			echo json_encode([
				'reload' => false,
				'error' => '404. Not found'
			]);
		}
	}

	public function dumpObject(): void
	{
		echo '<b>ROUTER:</b> <br>';
		print_r($this);
	}

	public function dumpRoute(string $httpMethod, string $uri): void
	{
		$path = $this->parseUri(uri: $uri);
		$routeKey = "$httpMethod $path";
		if (!isset($this->routes[$routeKey])) throw new \InvalidArgumentException("$routeKey doesn`t exist!");
		echo '<b>ROUTE:</b> <br>';
		print_r($this->routes[$routeKey]);
		[self::HANDLER => $controller, self::METHOD => $method] = $this->routes[$routeKey];
		echo '<b>CONTROLLER:</b> <br>';
		print_r($controller);
		echo '<b>METHOD:</b> <br>';
		echo $method;
	}
}