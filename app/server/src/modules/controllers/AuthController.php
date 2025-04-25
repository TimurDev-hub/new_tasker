<?php declare(strict_types=1);

namespace Controllers;

use Database\Database;
use Models\UserModel;
use Utils\Logger;

final class AuthController extends BaseController
{
	private const MIN_DATA_LEN = 4;
	private const MAX_DATA_LEN = 16;

	public function create(?string $uri = null): string
	{
		$data = $this->getClientData();

		if (!$this->validateText(data: $data)) {
			http_response_code(400);
			return json_encode([
				'reload' => false,
				'error' => 'Empty field or invalid characters'
			]);
		}

		$data = $this->sanitizeText(data: $data);

		if (!$this->checkSize(data: $data, min: self::MIN_DATA_LEN, max: self::MAX_DATA_LEN)) {
			http_response_code(400);
			return json_encode([
				'reload' => false,
				'error' => 'Values must be between 4 and 16 in length.'
			]);
		}

		try {
			$user = new UserModel(pdo: Database::connection());

			if (!$user->validateSpaces(data: $data)) {
				http_response_code(400);
				return json_encode([
					'reload' => false,
					'error' => 'Values must not have spaces'
				]);
			}

			if (!$user->findAccount(username: $data['user_name'])) {
				http_response_code(400);
				return json_encode([
					'reload' => false,
					'error' => "User `{$data['user_name']}` not exists"
				]);
			}

			$guest = $user->loginAccount(username: $data['user_name']);

			if (!password_verify($data['user_password'], $guest['user_password'])) {
				http_response_code(401);
				return json_encode([
					'reload' => false,
					'error' => 'Invalid username or password'
				]);
			}

			$cookie_domain = $_SERVER['HTTP_HOST'];

			setcookie('user_id', $guest['user_id'], time() + 3600, '/', $cookie_domain);
			setcookie('user_name', $guest['user_name'], time() + 3600, '/', $cookie_domain);

			http_response_code(200);
			return json_encode([
				'reload' => true
			]);

		} catch (\Exception $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			http_response_code(500);
			return json_encode([
				'reload' => false,
				'error' => 'Internal Server Error'
			]);
		}
	}

	public function delete(string $uri): string
	{
		try {
			$data = explode('/', $uri);
			$id = $data[count($data) - 1];

			if ($_COOKIE['user_id'] !== $id) throw new \Exception('Invalid cookie');

			$cookie_domain = $_SERVER['HTTP_HOST'];

			@setcookie('user_id', '', time() - 3600, '/', $cookie_domain);
			@setcookie('user_name', '', time() - 3600, '/', $cookie_domain);

			http_response_code(200);
			return json_encode([
				'reload' => true
			]);

		} catch (\Exception $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			http_response_code(500);
			return json_encode([
				'reload' => false,
				'error' => 'Internal Server Error'
			]);
		}
	}
}