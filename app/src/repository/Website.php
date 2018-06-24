<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Repository;
use Drago;

/**
 * Website repository.
 */
class Website extends Drago\Database\Connection
{
	/**
	 * @return array
	 */
	public function all()
	{
		return $this->db
			->fetchPairs('SELECT * FROM :prefix:settings');
	}

}
