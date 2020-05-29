<?php

declare(strict_types = 1);

namespace Repository;

use Dibi;
use Drago\Database;
use Entity\UsersEntity;


class UsersRepository extends Database\Connect
{
	use Database\Repository;

	/** @var string table name */
	private $table = UsersEntity::TABLE;

	/** @var int primary id */
	private $primaryId = UsersEntity::USER_ID;


	/**
	 * Find user by email.
	 * @return array|UsersEntity|null
	 * @throws Dibi\Exception
	 */
	public function findBy(string $email)
	{
		return $this->discover(UsersEntity::EMAIL, $email)
			->setRowClass(UsersEntity::class)->fetch();
	}
	
	/**
	 * Save record.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function save(UsersEntity $entity)
	{
		$id = $entity->getUserId();
		return $this->put($entity, $id);
	}
}
