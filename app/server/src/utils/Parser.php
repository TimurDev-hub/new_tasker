<?php declare(strict_types=1);

namespace Utils;

final class Parser
{
	private static ?Parser $parser = null;
	private const ENV = __DIR__ . '/../../../../.env';

	private function __construct() {}
	private function __clone() {}

	public static function get(): Parser
	{
		self::$parser ??= new Parser();
		return self::$parser;
	}

	private function getData(): array
	{
		return file(self::ENV, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);
	}

	public function loadEnv(): array
	{
		$data = [];
		$env = $this->getData();

		foreach ($env as $line) {
			$parsed = explode('=', $line, 2);
			$key = trim($parsed[0]);
			$value = trim($parsed[1]);

			$data[$key] = $value;
		}

		return $data;
	}
}