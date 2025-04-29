<?php declare(strict_types=1);

namespace Utils;

final class Parser
{
	private const ENV = __DIR__ . '/../../../../.env';

	private static function getData(): array
	{
		return file(self::ENV, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);
	}

	public static function loadEnv(): array
	{
		$data = [];
		$env = self::getData();

		foreach ($env as $line) {
			$parsed = explode('=', $line, 2);
			$key = trim($parsed[0]);
			$value = trim($parsed[1]);

			$data[$key] = $value;
		}

		return $data;
	}
}