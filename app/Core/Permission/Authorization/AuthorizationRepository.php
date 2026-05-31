<?php

declare(strict_types=1);

namespace App\Core\Permission\Authorization;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Fluent;
use Dibi\Row;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;


/** Repository for managing authorization data. */
#[Table(AuthorizationEntity::Table, AuthorizationEntity::PrimaryKey, class: AuthorizationEntity::class)]
class AuthorizationRepository
{
	/** @use Database<AuthorizationEntity> */
	use Database;

	public function __construct(
		protected readonly Connection $connection,
	) {
	}


	/** @throws AttributeDetectionException */
	public function getAll(): Fluent
	{
		return $this->read('*');
	}


	/** @return Row[] */
	public function getRolePermissions(int $roleId): array
	{
		return $this->connection->select("
				r.id,
				r.resource,
				r.privilege,
				r.description,
				CASE
					WHEN a.role_id IS NULL THEN 'deny'
					ELSE 'allow'
				END AS access
			")
			->from(ResourcesEntity::Table)->as('r')
			->leftJoin(AuthorizationEntity::Table)->as('a')
			->on('a.resource_id = r.id AND a.role_id = ?', $roleId)
			->orderBy('r.resource, r.privilege')
			->fetchAll();
	}


	/** @throws Exception */
	public function allow(int $roleId, int $resourceId): void
	{
		$this->connection->insert(AuthorizationEntity::Table, [
			AuthorizationEntity::ColumnRoleId => $roleId,
			AuthorizationEntity::ColumnResourceId => $resourceId,
		])
			->execute();
	}


	/** @throws Exception */
	public function deny(int $roleId, int $resourceId): void
	{
		$this->connection->delete(AuthorizationEntity::Table)
			->where('%n = ?', AuthorizationEntity::ColumnRoleId, $roleId)
			->where('%n = ?', AuthorizationEntity::ColumnResourceId, $resourceId)
			->execute();
	}
}
