<?php

declare(strict_types=1);

namespace App\UI\Install\Factory;

use Drago\Utils\ExtraArrayHash;


/**
 * Class representing database connection data.
 */
class DatabaseData extends ExtraArrayHash
{
	// Constants for database configuration keys
	public const Host = 'host';
	public const User = 'user';
	public const Password = 'password';
	public const Database = 'database';

	/** @var string $host Database host address */
	public string $host;

	/** @var string $user Database username */
	public string $user;

	/** @var string $password Database password */
	public string $password;

	/** @var string $database Database name */
	public string $database;
}
