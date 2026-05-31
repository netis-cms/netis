<?php

declare(strict_types=1);

namespace App\Install\Factory;

use Drago\Utils\ExtraArrayHash;


/** Class representing database connection data. */
class DatabaseValues extends ExtraArrayHash
{
	public const string
		Host = 'host',
		User = 'user',
		Password = 'password',
		Database = 'database';

	public string $host;
	public string $user;
	public string $password;
	public string $database;
}
