<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Login;
use Drago;

/**
 * User repository.
 */
class Repository extends Drago\Database\Connection
{
	/**
	 * @var string
	 */
	private $table = ':prefix:users';

	/**
	 * Find record by email.
	 * @param string
	 * @return array
	 */
	public function find($email)
	{
		return $this->db
			->select('*')
			->from($this->table)
			->where('email = ?', $email)
			->fetch();
	}

	/**
	 * Save record.
	 * @param mixed
	 * @return void
	 */
	public function save(Entity $entity)
	{
		if (!$entity->getId()) {
			return $this->db
				->insert($this->table, Iterator::set($entity))
				->execute();
		} else {
			return $this->db
				->update($this->table, Iterator::set($entity))
				->where('userId = ?', $entity->getId())
				->execute();
		}
	}

}
