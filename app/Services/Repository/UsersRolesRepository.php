<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Drago\Authorization\Entity\UsersRolesViewEntity;
use Drago\Database\Connect;
use Drago\Database\Repository;
use Nette\SmartObject;


class UsersRolesRepository extends Connect
{
	use SmartObject;
	use Repository;

	public string $table = UsersRolesViewEntity::TABLE;


	public function getUserRoles(int $userId): array
	{
		return $this->discover(UsersRolesViewEntity::USER_ID, $userId)
			->fetchPairs(null, UsersRolesViewEntity::ROLE);
	}
}
