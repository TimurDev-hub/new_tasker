<?php declare(strict_types=1);

namespace Controllers;

use Database\Database;
use Models\UserModel;
use Utils\Logger;

final class AuthController extends BaseController
{
	private const MIN_DATA_LEN = 4;
	private const MAX_DATA_LEN = 16;

	public function create(): void
	{
		return;
	}

	public function delete(): void
	{
		return;
	}
}