<?php declare(strict_types=1);

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
		if (!$this->validateId(data: [$userId])) return false;

		try {
			$stmt = $this->pdo->prepare("SELECT COUNT(task_id) FROM tasks WHERE user_id = ?");
			$stmt->execute([$userId]);
			return $stmt->fetchColumn();

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			return false;
		}
	}

	public function createTask(int $userId, array $data): bool
	{
		if (!$this->validateId(data: [$userId])) return false;
		if (!$this->validateText(data: [$data['task_title'], $data['task_content']])) return false;

		try {
			$stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, task_title, task_content) VALUES(?, ?, ?)");
			return $stmt->execute([$userId, $data['task_title'], $data['task_content']]);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			return false;
		}
	}

	public function getTasks(int $userId): array|false
	{
		if (!$this->validateId(data: [$userId])) return false;

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
		if (!$this->validateId(data: [$userId, $taskId])) return false;

		try {
			$stmt = $this->pdo->prepare("DELETE FROM tasks WHERE user_id = ? AND task_id = ?");
			return $stmt->execute([$userId, $taskId]);

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			return false;
		}
	}
}