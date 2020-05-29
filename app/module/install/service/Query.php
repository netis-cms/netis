<?php

declare(strict_types = 1);

namespace Module\Install\Service;

use Drago;


/**
 * Install database tables.
 */
class Query extends Drago\Database\Connect
{
	/**
	 * Add tables to database.
	 * @throws \Dibi\Exception
	 */
	public function addTable(string $args): void
	{
		$this->db->query($args);
	}


	/**
	 * Add records.
	 * @throws \Dibi\Exception
	 */
	public function addRecord(string $table, array $args): void
	{
		$this->db->insert($table, $args)
			->execute();
	}


	/**
	 * Verify that a table exists by name.
	 * @throws \Exception
	 */
	public function isTable(string $table): bool
	{
		$query = $this->db->getDatabaseInfo()
			->hasTable($table);

		if ($query) {
			throw new \Exception('Sorry, but collide in a database table names.', 1);
		}
		return $query;

	}
}
