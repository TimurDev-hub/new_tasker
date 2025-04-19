<?php declare(strict_types=1);

namespace Models;

use Utils\Logger;

final class UserModel
{
	private \PDO $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function validateUserData(array $data): bool
	{
		foreach ($data as $item) {
			if (str_contains($item, ' ')) return false;
		}
		return true;
	}

	public function findAccount(string $username): bool
	{
		try {
			$stmt = $this->pdo->prepare("SELECT 1 FROM users WHERE user_name = ? LIMIT 1");
			$stmt->execute([$username]);
			return (bool) $stmt->fetchColumn();

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			return false;
		}
	}

	public function createAccount(string $username, string $password): bool
	{
		$password = password_hash($password, PASSWORD_DEFAULT);

		try {
			$stmt = $this->pdo->prepare("INSERT INTO users (user_name, user_password) VALUES(?, ?)");
			return $stmt->execute([$username, $password]);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			return false;
		}
	}

	public function loginAccount(string $username, string $password): bool
	{
		try {
			$stmt = $this->pdo->prepare("SELECT user_id, user_name, user_password FROM users WHERE user_name = ? AND user_password = ? LIMIT 1");
			$stmt->execute([$username, $password]);
			return $stmt->fetch(\PDO::FETCH_ASSOC);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::USER_FILE);
			return false;
		}
	}

	public function deleteAccount(int $userId): bool
	{
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