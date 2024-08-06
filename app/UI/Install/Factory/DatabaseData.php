<?php

declare(strict_types=1);

namespace App\UI\Install\Factory;

use Drago\Utils\ExtraArrayHash;


class DatabaseData extends ExtraArrayHash
{
	public const Host = 'host';
	public const User = 'user';
	public const Password = 'password';
	public const Database = 'database';

	public string $host;
	public string $user;
	public string $password;
	public string $database;
}
