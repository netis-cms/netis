<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Repository;

use Drago;
use Dibi;

/**
 * Website repository.
 */
class Website extends Drago\Database\Connection
{
	/**
	 * @return array
	 * @throws Dibi\Exception
	 */
	public function all()
	{
		return $this->db
			->fetchPairs('SELECT * FROM :prefix:settings');
	}
}
