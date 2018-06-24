<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Repository;

use Drago;
use Entity;

/**
 * Settings repository.
 */
class Settings extends Drago\Database\Connection
{
	public function save(Entity\Settings $entity)
	{
		foreach ($entity as $key => $value) {
			$this->db
				->query('
					UPDATE :prefix:settings SET %a', ['value' => $value], '
					WHERE name = ?', $key);
		}
	}

}
