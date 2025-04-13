<?php

namespace Models;

use Utils\Logger;

final class TaskModel extends __Model
{
	private \PDO $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function countTasks(int $userId): int|false
	{
		if ($this->validateId(id: $userId)) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT COUNT(task_id) FROM tasks WHERE user_id = ?");
			$stmt->execute([$userId]);
			return $stmt->fetchColumn();

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			return false;
		}
	}

	public function createTask(array $data): bool
	{
		return 1;
	}

	public function getTasks(int $userId): array|false
	{
		if ($this->validateId(id: $userId)) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT task_id, task_title, task_content WHERE user_id = ?");
			$stmt->execute([$userId]);
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			return false;
		}
	}

	public function deleteTask(int $userId, int $taskId): bool
	{
		return 1;
	}
}