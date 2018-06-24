<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Repository;

use Drago;
use Entity;
use Drago\Database\Iterator;

/**
 * User repository.
 */
class User extends Drago\Database\Connection
{
	/**
	 * @param string $email
	 * @return array
	 */
	public function find($email)
	{
		return $this->db
			->fetch('
				SELECT * FROM :prefix:users
				WHERE email = ?', $email);
	}

	public function save(Entity\User $entity)
	{
		if ($entity->getId()) {
			$this->db
				->query('
					UPDATE :prefix:users SET %a', Iterator::toArray($entity), '
					WHERE userId = ?', $entity->getId());
		}
	}

}
