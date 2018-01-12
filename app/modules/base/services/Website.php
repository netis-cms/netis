<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Base\Repository;
use Drago;

/**
 * Website repository.
 */
class Website extends Drago\Database\Connection
{
	/**
	 * @var String
	 */
	private $table = ':prefix:settings';

	/**
	 * Returns all records.
	 * @return array
	 */
	public function all()
	{
		return $this->db
			->select('*')
			->from($this->table)
			->fetchPairs();
	}

}
