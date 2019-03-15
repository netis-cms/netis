<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Repository;

use Drago;
use Dibi;

/**
 * Menu repository.
 */
class Menu extends Drago\Database\Connection
{
	/**
	 * @return Dibi\Result|int
	 * @throws Dibi\Exception
	 */
	public function all()
	{
		return $this->db
			->query('
				SELECT * FROM :prefix:menu AS m LEFT JOIN :prefix:menu_category AS c
				USING (categoryId) ORDER BY menuId ASC');
	}


	/**
	 * @return Dibi\Result|int
	 * @throws Dibi\Exception
	 */
	public function category()
	{
		return $this->db
			->query('
				SELECT * FROM :prefix:menu AS m LEFT JOIN :prefix:menu_category AS c
				USING (categoryId) GROUP BY categoryId');
	}
}
