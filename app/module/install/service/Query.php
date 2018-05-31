<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Install\Service;

use Drago;
use Exception;

/**
 * Install database tables.
 */
class Query extends Drago\Database\Connection
{
	// Exception error code.
	const COLLIDE_TABLE = 1;

	/**
	 * Add tables to database.
	 * @param array $args
	 * @return void
	 */
	public function addTable($args)
	{
		return $this->db
			->query($args);
	}

	/**
	 * Add records.
	 * @param string $table
	 * @param array $args
	 * @return void
	 */
	public function addRecord($table, $args)
	{
		return $this->db
			->insert($table, $args)
			->execute();
	}

	/**
	 * Checking the existence of a table in the database.
	 * @param string $name
	 * @throws Exception
	 */
	public function isExistTable($name)
	{
		if ($this->db->query('SHOW TABLES LIKE %like~', $name)->fetch() ? TRUE : FALSE) {
			throw new Exception('Sorry, but collide in a database table names.', self::COLLIDE_TABLE);
		}
	}

}
