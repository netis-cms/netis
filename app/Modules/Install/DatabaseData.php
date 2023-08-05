<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class DatabaseData extends ExtraArrayHash
{
	use SmartObject;

	public const HOST = 'host';
	public const USER = 'user';
	public const PASSWORD = 'password';
	public const DATABASE = 'database';

	public string $host;
	public string $user;
	public string $password;
	public string $database;
}
