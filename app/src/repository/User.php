<?php

declare(strict_types = 1);

namespace Repository;

use Dibi;
use Drago\Database\Connection;
use Drago\Database\Repository;
use Entity\UserEntity;


/**
 * User repository.
 */
class UserRepository extends Connection
{
	use Repository;

	/** @var string table name */
	private $table = UserEntity::TABLE;

	/** @var int primary id */
	private $primaryId = UserEntity::USER_ID;


	/**
	 * Find user by email.
	 * @return array|UserEntity|null
	 * @throws Dibi\Exception
	 */
	public function findBy(string $email)
	{
		return $this->discover(UserEntity::EMAIL, $email)
			->setRowClass(UserEntity::class)->fetch();
	}
	
	/**
	 * Save record.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function save(UserEntity $entity)
	{
		$id = $entity->getUserId();
		return $this->add($entity, $id);
	}
}
