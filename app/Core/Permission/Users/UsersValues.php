<?php

declare(strict_types=1);

namespace App\Core\Permission\Users;

use Drago\Utils\ExtraArrayHash;


/** Users values. */
class UsersValues extends ExtraArrayHash
{
	public const string
		UserId = 'user_id',
		RoleId = 'role_id';

	public int $user_id;

	/** @var array<int, int> */
	public array $role_id;
}
