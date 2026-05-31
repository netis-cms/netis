<?php

declare(strict_types=1);

namespace App\Core\Permission\Users;

use Drago\Database\Entity;


/** Users roles entity. */
class UsersRolesEntity extends Entity
{
	public const string
		Table = 'users_roles',
		ColumnUserId = 'user_id',
		ColumnRoleId = 'role_id';

	public int $user_id;
	public int $role_id;
}
