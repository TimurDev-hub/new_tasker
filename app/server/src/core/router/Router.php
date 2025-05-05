<?php declare(strict_types=1);

namespace Router;

use Utils\Logger;
use Controllers\BaseController;

final class Router
{
	public const HANDLER = 'handler'; // Константы много-где будут к месту, напоминание для себя-любимого;
	public const METHOD = 'method';

	private array $routes = [];

	public function setRoute(string $httpMethod, string $uri, array $request): void
	{
		[self::HANDLER => $handlerClass, self::METHOD => $method] = $request; // Деструктуризация ассоциативного массива, классная штука. Вот кстати и пригодились константы;

		if (!class_exists($handlerClass)) throw new \InvalidArgumentException("Controller '$handlerClass' doesn`t exist!");

		$this->routes["$httpMethod $uri"] = [
			self::HANDLER => new $handlerClass(), // При создании объектов лучше всегда указывать скобки, чтоб не объебаться потом лишний раз;
			self::METHOD => $method
		];
	}

	private function parseUri(string $uri): string
	{
		return parse_url(trim($uri), \PHP_URL_PATH);
	}

	public function dispatchRoutes(string $httpMethod, string $uri): void
	{
		header("Content-Type: application/json");

		try {
			$path = $this->parseUri(uri: $uri);
			$routeKey = "$httpMethod $path";

			if (!isset($this->routes[$routeKey])) {
				if (preg_match('/^DELETE\s\/api\/auth\/[0-9]+$/s', $routeKey)) $routeKey = 'DELETE /api/auth/{id}';
				elseif (preg_match('/^DELETE\s\/api\/user\/[0-9]+\/del$/s', $routeKey)) $routeKey = 'DELETE /api/user/{id}';
				elseif (preg_match('/^DELETE\s\/api\/task(\/\w+){2}$/s', $routeKey)) $routeKey = 'DELETE /api/task/{user}/{task}';
				elseif (preg_match('/^GET\s\/api\/task\/[0-9]+$/s', $routeKey)) $routeKey = 'GET /api/task/{id}';
				else throw new \InvalidArgumentException("Route '$routeKey' doesn`t exist!");
			}

			[self::HANDLER => $controller, self::METHOD => $method] = $this->routes[$routeKey]; // Снова деструктуризация ассоциативного массива, хочу для себя сделать акцент на использование констант в таком ключе - здорово ведь;

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
}