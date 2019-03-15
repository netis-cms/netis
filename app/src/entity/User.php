<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Entity;

use Drago;

/**
 * User entity.
 */
class User extends Drago\Database\Entity
{
	/**
	 * @var string
	 */
	public $password;
}
