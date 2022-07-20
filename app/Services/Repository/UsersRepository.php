<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Services\Entity\UsersEntity;
use Dibi\Connection;
use Dibi\Exception;
use Dibi\Row;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Repository;
use Nette\SmartObject;


#[Table(UsersEntity::TABLE, UsersEntity::PRIMARY)]
class UsersRepository
{
	use SmartObject;
	use Repository;

	public function __construct(
		protected Connection $db,
	) {
	}


	/**
	 * Find user by email.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function find(string $email): array|Row|UsersEntity|null
	{
		return $this->discover(UsersEntity::EMAIL, $email)
			->execute()->setRowClass(UsersEntity::class)
			->fetch();
	}
}
