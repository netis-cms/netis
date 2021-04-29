<?php

declare(strict_types=1);

namespace Repository;

use App\Entity\UsersEntity;
use Dibi\Exception;
use Drago\Database\Connect;
use Drago\Database\Repository;
use Nette\SmartObject;


class UsersRepository extends Connect
{
	use SmartObject;
	use Repository;

	public string $table = UsersEntity::TABLE;
	public string $primary = UsersEntity::PRIMARY;


	/**
	 * Find user by email.
	 * @throws Exception
	 */
	public function find(string $email): array|UsersEntity|null
	{
		return $this->discover(UsersEntity::EMAIL, $email)->execute()
			->setRowClass(UsersEntity::class)->fetch();
	}
}
