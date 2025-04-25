<?php declare(strict_types=1);

namespace Controllers;

abstract class BaseController
{
	protected function getClientData(): array
	{
		$json = file_get_contents('php://input');
		return json_decode($json, true);
	}

	protected function checkSize(array $data, int $min, int $max): bool
	{
		foreach ($data as $item) {
			if (strlen($item) < $min || strlen($item) > $max) return false;
		}

		return true;
	}

	protected function validateId(array $data): bool
	{
		if (empty($data)) return false;

		foreach ($data as $id) {
			if (!is_int($id) || $id <= 0) return false;
		}

		return true;
	}

	protected function validateText(array $data): bool
	{
		if (empty($data)) return false;

		foreach ($data as $string) {
			if (!is_string($string) && !is_numeric($string)) return false;
			if (!preg_match('/^[a-z0-9\-_=+%\\@#!\/()\s]+$/is', $string)) return false;
			if (trim($string) === '') return false;
		}

		return true;
	}

	protected function sanitizeText(array $data): array
	{
		$clearedData = [];

		foreach ($data as $string) {
			$clearedData[] = htmlspecialchars(trim($string));
		}

		return $clearedData;
	}
}