<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class DatabaseData extends ExtraArrayHash
{
	use SmartObject;

	public const Host = 'host';
	public const User = 'user';
	public const Password = 'password';
	public const Database = 'database';

	public string $host;
	public string $user;
	public string $password;
	public string $database;
}
