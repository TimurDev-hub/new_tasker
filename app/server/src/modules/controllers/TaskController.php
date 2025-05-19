<?php declare(strict_types=1);

namespace Controllers;

use Database\Database;
use Models\TaskModel;
use Utils\Logger;

final class TaskController extends BaseController
{
	private const MIN_DATA_LEN = 1;
	private const MAX_TITLE_LEN = 16;
	private const MAX_CONTENT_LEN = 100;
	private const MAX_TASKS_VAL = 5;

	/**
	 * @param '/api/task'
	 * @return string;
	*/
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

		if (!$this->checkSize(data: [$data['task_title']], min: self::MIN_DATA_LEN, max: self::MAX_TITLE_LEN)) {
			http_response_code(400);
			return json_encode([
				'reload' => false,
				'error' => 'Title must be between 4 and 16 in length.'
			]);
		}
		if (!$this->checkSize(data: [$data['task_content']], min: self::MIN_DATA_LEN, max: self::MAX_CONTENT_LEN)) {
			http_response_code(400);
			return json_encode([
				'reload' => false,
				'error' => 'Content must be between 4 and 100 in length.'
			]);
		}

		$userId = (int) $_COOKIE['user_id'];
		if (!isset($userId)) return json_encode(['reload' => true]);

		try {
			$task = new TaskModel(pdo: Database::connection());

			if ($task->countTasks(userId: $userId) > self::MAX_TASKS_VAL) {
				http_response_code(400);
				return json_encode([
					'reload' => false,
					'error' => 'You can`t have more than 5 tasks'
				]);
			}

			if (!$task->createTask(userId: $userId, taskTitle: $data['task_title'], taskContent: $data['task_content'])) {
				http_response_code(400);
				return json_encode([
					'reload' => true,
					'error' => 'Failed to create a task'
				]);
			}

			http_response_code(201);
			return json_encode([
				'reload' => true,
				'status' => 'Created successfully!'
			]);

		} catch (\Exception $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			http_response_code(500);
			return json_encode([
				'reload' => false,
				'error' => 'Internal Server Error'
			]);
		}
	}

	/**
	 * @param '/api/task/{id}'
	 * @return array;
	*/
	public function index(string $uri): string
	{
		try {
			$data = explode('/', $uri);
			$userId = (int) $data[count($data) - 1];

			if (!$this->validateId(data: [$userId])) throw new \InvalidArgumentException('Invalid User ID');
			if ((int) $_COOKIE['user_id'] !== $userId) throw new \InvalidArgumentException('Invalid Cookie');

			$task = new TaskModel(pdo: Database::connection());
			$tasks = $task->getTasks(userId: $userId);

			http_response_code(200);
			return json_encode([
				'reload' => true,
				'tasks' => $tasks
			]);

		} catch (\Exception $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			http_response_code(500);
			return json_encode([
				'reload' => false,
				'error' => 'Internal Server Error'
			]);
		}
	}

	/**
	 * @param '/api/task/{user}/{task}'
	 * @return string;
	*/
	public function delete(string $uri): string
	{
		$data = explode('/', $uri);
		$userId = (int) $data[count($data) - 2];
		$taskId = (int) $data[count($data) - 1];

		if (!$this->validateId(data: [$userId])) throw new \InvalidArgumentException('Invalid User ID');
		if (!$this->validateId(data: [$taskId])) throw new \InvalidArgumentException('Invalid User ID');

		try {
			$task = new TaskModel(pdo: Database::connection());

			if ($task->countTasks(userId: $userId) === 0) {
				http_response_code(400);
				return json_encode([
					'reload' => true,
					'error' => 'You must have 1 or more tasks to delete them'
				]);
			}

			if (!$task->deleteTask(userId: $userId, taskId: $taskId)) {
				http_response_code(400);
				return json_encode([
					'reload' => true,
					'error' => 'Failed to delete a task'
				]);
			}

			http_response_code(200);
			return json_encode([
				'reload' => true,
				'status' => 'Deleted successfully!'
			]);

		} catch (\Exception $exc) {
			Logger::handleError(exc: $exc, file: Logger::TASK_FILE);
			http_response_code(500);
			return json_encode([
				'reload' => false,
				'error' => 'Internal Server Error'
			]);
		}
	}
}