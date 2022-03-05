<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Drago\Attr\Table;
use Drago\Authorization\Service\Entity\UsersRolesViewEntity;
use Drago\Database\Connect;


#[Table(UsersRolesViewEntity::TABLE)]
class UsersRolesRepository extends Connect
{
	public function getUserRoles(int $userId): array
	{
		return $this->discover(UsersRolesViewEntity::USER_ID, $userId)
			->fetchPairs(null, UsersRolesViewEntity::ROLE);
	}
}
