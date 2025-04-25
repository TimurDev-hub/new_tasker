<?php declare(strict_types=1);

namespace Controllers;

use Database\Database;
use Models\UserModel;
use Utils\Logger;

final class UserController extends BaseController
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

			if ($user->validateSpaces(data: $data)) {
				http_response_code(400);
				return json_encode([
					'reload' => false,
					'error' => 'Values must not have spaces'
				]);
			}

			if ($user->findAccount(username: $data['user_name'])) {
				http_response_code(400);
				return json_encode([
					'reload' => false,
					'error' => "The name {$data['user_name']} is already taken"
				]);
			}

			if (!$user->createAccount(username: $data['user_name'], password: $data['user_password'])) {
				http_response_code(400);
				return json_encode([
					'reload' => false,
					'error' => 'Failed to create account'
				]);
			}

			http_response_code(201);
			return json_encode([
				'reload' => false,
				'status' => 'Created successfully!'
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

	public function delete(): void
	{
		return;
	}
}