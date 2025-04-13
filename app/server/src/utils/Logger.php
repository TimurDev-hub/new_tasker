<?php

namespace Utils;

final class Logger
{
	public const DB_FILE = __DIR__ . '/../core/logs/db.log';
	public const TASK_FILE = __DIR__ . '/../core/logs/task.log';
	public const USER_FILE = __DIR__ . '/../core/logs/user.log';

	private static function setPrefix(): string
	{
		return date('Y-m-d H:i:s') . ' SERVER ERROR: ';
	}

	private static function getLog(\Throwable $exc): string
	{
		return 'M => ' . $exc->getMessage() . '; F=> ' . $exc->getFile() . '; L=> ' . $exc->getLine();
	}

	public static function handleError(\Throwable $exc, string $file): void
	{
		$message = self::setPrefix() . self::getLog(exc: $exc) . PHP_EOL;
		error_log($message, 3, $file);
	}
}