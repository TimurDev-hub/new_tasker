<?php

namespace Database;

use Utils\{Parser, Logger};

final class Database
{
	private static \PDO $pdo;

	public static function connection(): \PDO
	{
		try {
			self::$pdo = new \PDO(dsn: "pgsql:host=\$host;port=\$port;dbname=\$db;user=\$user;password=\$pass");
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return self::$pdo;

		} catch (\PDOException $exc) {
			Logger::handleError(exc: $exc, file: Logger::DB_FILE);
		}
	}
}