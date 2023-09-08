<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class DatabaseData extends ExtraArrayHash
{
	use SmartObject;

	public const host = 'host';
	public const user = 'user';
	public const password = 'password';
	public const database = 'database';

	public string $host;
	public string $user;
	public string $password;
	public string $database;
}
