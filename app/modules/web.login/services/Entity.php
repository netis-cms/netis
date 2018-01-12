<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Login;
use Drago;

/**
 * User entity.
 */
class Entity extends Drago\Database\Entity
{
	/**
	 * @var string
	 */
	public $password;
}
