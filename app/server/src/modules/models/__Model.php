<?php

namespace Models;

abstract class __Model
{
	protected function prepareData(array &$data): bool
	{
		if (empty($data)) return false;

		foreach ($data as &$item) {
			$item = trim($item);
			if (empty($item)) return false;
			$item = htmlspecialchars($item);
		}

		return true;
	}

	protected function validateId(int $id): bool
	{
		if (empty($id)) return false;
		elseif (!is_numeric($id)) return false;
		else return true;
	}

	private function validateText(string $text, int $min, int $max): bool
	{
		if (empty($text)) return false;
		elseif (!is_string($text)) return false;
		elseif (strlen($text) < $min || strlen($text) > $max) return false;
		else return true;
	}
}