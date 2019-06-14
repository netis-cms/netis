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
	public $table = UserEntity::TABLE;

	/** @var int primary id */
	public $primaryId = UserEntity::USER_ID;


	/**
	 * Find user by email.
	 * @return array|UserEntity|null
	 * @throws Dibi\Exception
	 */
	public function FindByEmail(string $email)
	{
		return $this
			->find(UserEntity::EMAIL, $email)->execute()
			->setRowClass(UserEntity::class)
			->fetch();
	}
	
	/**
	 * Save record.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function save(UserEntity $entity)
	{
		$id = $entity->getUserId();
		$query = $id
			? $this->save($entity->getModify(), $this->primaryKey, $id)
			: $this->save($entity->getModify());

		return $query->execute();
	}
}
