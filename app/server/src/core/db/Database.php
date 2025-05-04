<?php declare(strict_types=1);

namespace Database;

use Utils\{Parser, Logger};

final class Database
{
	private static \PDO $pdo;

	public static function connection(): \PDO
	{
		['DB_HOST' => $host, 'DB_PORT' => $port, 'DB_NAME' => $db, 'DB_USER' => $user, 'DB_PASS' => $pass] = Parser::loadEnv();

		try {
			self::$pdo = new \PDO(dsn: "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass");
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return self::$pdo;

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::DB_FILE);
			throw $exc;
		}
	}
}
