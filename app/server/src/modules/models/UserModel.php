<?php declare(strict_types=1);

namespace Models;

use Utils\Logger;

final class UserModel extends __Model
{
	private \PDO $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function findAccount(string $username): bool
	{
		try {
			$stmt = $this->pdo->prepare("SELECT 1 FROM users WHERE user_name =? LIMIT 1");
			$stmt->execute([$username]);
			return (bool) $stmt->fetchColumn();

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			return false;
		}
	}

	public function createAccount(array $data): bool
	{
		if (!$this->validateText(data: [$data['user_name'], $data['user_password']])) return false;
		if ($this->findAccount(username: $data['user_name'])) return false;

		$password = password_hash($data['user_password'], PASSWORD_DEFAULT);

		try {
			$stmt = $this->pdo->prepare("INSERT INTO users (user_name, user_password) VALUES(?, ?)");
			return $stmt->execute([$data['user_name'], $password]);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			return false;
		}
	}

	public function loginAccount(array $data): bool
	{
		if (!$this->validateText(data: [$data['user_name'], $data['user_password']])) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT user_id, user_name, user_password FROM users WHERE user_name = ? AND user_password = ? LIMIT 1");
			$stmt->execute([$data['user_name'], $data['user_password']]);
			return $stmt->fetch(\PDO::FETCH_ASSOC);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			return false;
		}
	}

	public function deleteAccount(int $userId): bool
	{
		if (!$this->validateId(data: [$userId])) return false;

		try {
			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare("DELETE FROM tasks WHERE user_id = ?");
			$stmt->execute([$userId]);
			$stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
			$stmt->execute([$userId]);

			$this->pdo->commit();
			return true;

		} catch (\PDOException $exc) {
			$this->pdo->rollBack();
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			return false;
		}
	}
}