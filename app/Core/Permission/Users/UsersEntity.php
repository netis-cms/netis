<?php

declare(strict_types=1);

namespace App\Core\Permission\Users;

use Drago\Database\Entity;


/** Users entity. */
class UsersEntity extends Entity
{
	public const string
		Table = 'users',
		PrimaryKey = 'id',
		ColumnUsername = 'username';

	public int $id;
	public string $username;
}
