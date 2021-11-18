<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Services\Entity\UsersEntity;
use Dibi\Row;
use Drago\Attr\Table;
use Drago\Database\Connect;


#[Table(UsersEntity::TABLE, UsersEntity::PRIMARY)]
class UsersRepository extends Connect
{
	/**
	 * Find user by email.
	 */
	public function find(string $email): array|Row|UsersEntity|null
	{
		return $this->discover(UsersEntity::EMAIL, $email)
			->fetch();
	}
}
