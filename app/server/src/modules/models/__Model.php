<?php declare(strict_types=1);

namespace Models;

abstract class __Model
{
	protected function validateId(array $data): bool
	{
		if (empty($data)) return false;

		foreach ($data as $id) {
			if (!is_int($id) || $id <= 0) return false;
		}

		return true;
	}

	protected function validateText(array &$data): bool
	{
		if (empty($data)) return false;

		foreach ($data as &$string) {
			if (!is_string($string) && !is_numeric($string)) return false;
			$string = trim((string) $string);
			if ($string === '') return false;
		}

		return true;
	}
}