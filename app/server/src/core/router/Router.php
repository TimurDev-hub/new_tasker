<?php declare(strict_types=1);

namespace Router;

use Utils\Logger;
use Controllers\BaseController;

final class Router
{
	public const HANDLER = 'handler'; // Константы много-где будут к месту, напоминание для себя-любимого;
	public const METHOD = 'method';

	private static ?Router $router = null;
	private array $routes = [];

	private function __construct() {}
	private function __clone() {}

	public static function getInstance(): Router
	{
		self::$router ??= new Router();
		return self::$router;
	}

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

	private function uriPatternToRegex(string $uriPattern): string
	{
		$pattern = str_replace('/', '\\/', $uriPattern);
		$pattern = preg_replace('/{\w+}/', '\w+', $pattern);
		return '/^' . $pattern . '$/s';
	}

	private function findMatchingRoute(string $httpMethod, string $uri): ?string
	{
		foreach (array_keys($this->routes) as $routeKey) {
			list($routeHttpMethod, $routeUriPattern) = explode(' ', $routeKey, 2);
			if ($httpMethod !== $routeHttpMethod) continue;

			$pattern = $this->uriPatternToRegex(uriPattern: $routeUriPattern);
			if (preg_match($pattern, $uri)) return $routeKey;
		}

		return null;
	}

	public function dispatchRoutes(string $httpMethod, string $uri): void
	{
		header("Content-Type: application/json"); // Важно;

		try {
			$path = $this->parseUri(uri: $uri);
			$routeKey = "$httpMethod $path";

			if (!isset($this->routes[$routeKey])) {
				$route = $this->findMatchingRoute(httpMethod: $httpMethod, uri: $path) ?? throw new \InvalidArgumentException("Route '$routeKey' doesn`t exist!");
				$routeKey = $route;
			}

			[self::HANDLER => $controller, self::METHOD => $method] = $this->routes[$routeKey]; // Снова деструктуризация ассоциативного массива, хочу для себя сделать акцент на использование констант в таком ключе - здорово ведь;

			if (!$controller instanceof BaseController) throw new \InvalidArgumentException("Controller '$controller' doesn`t correct!");
			if (!method_exists($controller, $method)) throw new \InvalidArgumentException("Method '$method' doesn`t exist in controller '$controller'!");

			echo $controller->$method($path);

		} catch (\Throwable $exc) {
			Logger::handleError(exc: $exc, file: Logger::ROUTER_FILE);
			http_response_code(404);
			echo json_encode([
				'reload' => false,
				'error' => '404. Not found'
			]);
		}
	}
}