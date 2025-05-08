<?php declare(strict_types=1);

namespace Models;

use Utils\Logger;

final class TaskModel
{
	private \PDO $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function countTasks(int $userId): int
	{
		try {
			$stmt = $this->pdo->prepare("SELECT COUNT(task_id) FROM tasks WHERE user_id = ?");
			$stmt->execute([$userId]);
			return $stmt->fetchColumn();

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			throw $exc;
		}
	}

	public function createTask(int $userId, string $taskTitle, string $taskContent): bool
	{
		try {
			$stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, task_title, task_content) VALUES(?, ?, ?)");
			return $stmt->execute([$userId, $taskTitle, $taskContent]);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			throw $exc;
		}
	}

	public function getTasks(int $userId): array
	{
		try {
			$stmt = $this->pdo->prepare("SELECT task_id, task_title, task_content FROM tasks WHERE user_id = ?");
			$stmt->execute([$userId]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			throw $exc;
		}
	}

	public function deleteTask(int $userId, int $taskId): bool
	{
		try {
			$stmt = $this->pdo->prepare("DELETE FROM tasks WHERE user_id = ? AND task_id = ?");
			return $stmt->execute([$userId, $taskId]);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			throw $exc;
		}
	}
}