<?php

declare(strict_types=1);

namespace App\Core\Permission\Users;

use Dibi\Connection;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;


/** Repository for managing users data. */
#[Table(UsersEntity::Table, UsersEntity::PrimaryKey, class: UsersEntity::class)]
class UserRepository
{
	/** @phpstan-use Database<UsersEntity> */
	use Database;

	public function __construct(
		protected readonly Connection $connection,
	) {
	}


	/**
	 * @return array<int, string>
	 * @throws AttributeDetectionException
	 */
	public function getAllUsers(): array
	{
		return $this->read('*')
			->fetchPairs(UsersEntity::PrimaryKey, UsersEntity::ColumnUsername);
	}
}
