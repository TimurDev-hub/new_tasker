<?php declare(strict_types=1);

namespace Utils;

final class Logger
{
	public const DB_FILE = __DIR__ . '/../core/logs/db.log';
	public const ROUTER_FILE = __DIR__ . '/../core/logs/router.log';
	public const TASK_FILE = __DIR__ . '/../core/logs/task.log';
	public const USER_FILE = __DIR__ . '/../core/logs/user.log';

	private static function setPrefix(): string
	{
		return date('Y-m-d H:i:s') . " SERVER ERROR:\n";
	}

	private static function getLog(\Throwable $exc): string
	{
		return 'M => ' . $exc->getMessage() . ";\nF=> " . $exc->getFile() . ";\nL=> " . $exc->getLine() . ";\nT=>" . $exc->getTraceAsString();
	}

	public static function handleError(\Throwable $exc, string $file): void
	{
		$message = self::setPrefix() . self::getLog(exc: $exc) . PHP_EOL;
		error_log($message, 3, $file);
	}
}