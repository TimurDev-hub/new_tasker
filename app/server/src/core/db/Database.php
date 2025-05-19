<?php declare(strict_types=1);

namespace Database;

use Utils\{Parser, Logger};

final class Database
{
	private static ?\PDO $pdo = null;

	private function __construct() {}
	private function __clone() {}

	public static function connection(): \PDO
	{
		try {
			if (self::$pdo === null) {
				['DB_HOST' => $host,
				'DB_PORT' => $port,
				'DB_NAME' => $db,
				'DB_USER' => $user,
				'DB_PASS' => $pass] = Parser::get()->loadEnv();

				self::$pdo = new \PDO(dsn: "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass");
				self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			}

			return self::$pdo;

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::DB_FILE);
			throw $exc;
		}
	}
}
