<?php

declare(strict_types=1);

namespace App\Services\Repository;

use Dibi\Connection;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Authorization\Control\Access\UsersRolesViewEntity;
use Drago\Database\Repository;
use Nette\SmartObject;


#[Table(UsersRolesViewEntity::TABLE)]
class UsersRolesRepository
{
	use SmartObject;
	use Repository;

	public function __construct(
		protected Connection $db,
	) {
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getUserRoles(int $userId): array
	{
		return $this->discover(UsersRolesViewEntity::USER_ID, $userId)
			->fetchPairs(null, UsersRolesViewEntity::ROLE);
	}
}
