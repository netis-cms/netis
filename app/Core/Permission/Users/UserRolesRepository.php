<?php

declare(strict_types=1);

namespace App\Core\Permission\Users;

use App\Core\Permission\Roles\RolesEntity;
use Dibi\Connection;
use Dibi\Exception;
use Dibi\Fluent;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;


/** Repository for managing user roles data. */
#[Table(UsersRolesEntity::Table, UsersRolesEntity::ColumnUserId, class: UsersRolesEntity::class)]
class UserRolesRepository
{
	/** @phpstan-use Database<UsersRolesEntity> */
	use Database;

	public function __construct(
		protected readonly Connection $connection,
	) {
	}


	/**
	 * @return UsersRolesEntity[]
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function getUserRoles(int $userId): array
	{
		return $this->find(UsersRolesEntity::ColumnUserId, $userId)
			->recordAll();
	}


	/** @throws AttributeDetectionException */
	public function getAllUserRoles(): Fluent
	{
		return $this->read('ur.user_id id, u.username, GROUP_CONCAT(r.description SEPARATOR ", ") AS roles')->as('ur')
			->innerJoin(UsersEntity::Table)->as('u')->on('ur.user_id = u.id')
			->innerJoin(RolesEntity::Table)->as('r')->on('ur.role_id = r.id')
			->groupBy('u.id, u.username');
	}
}
