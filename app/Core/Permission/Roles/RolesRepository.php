<?php

declare(strict_types=1);

namespace App\Core\Permission\Roles;

use Dibi\Connection;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;
use Drago\Database\ExtraFluent;


/** Repository for managing roles data. */
#[Table(RolesEntity::Table, RolesEntity::PrimaryKey, class: RolesEntity::class)]
class RolesRepository
{
	/** @use Database<RolesEntity> */
	use Database;

	public function __construct(
		protected readonly Connection $connection,
	) {
	}


	/**
	 * @return array<int, string>
	 * @throws AttributeDetectionException
	 */
	public function getAllRoles(): array
	{
		return $this->read('*')
			->fetchPairs(RolesEntity::PrimaryKey, RolesEntity::ColumnDescription);
	}


	/**
	 * @return ExtraFluent<RolesEntity>
	 * @throws AttributeDetectionException
	 */
	public function getRolesFluent(): ExtraFluent
	{
		return $this->read('*');
	}
}
