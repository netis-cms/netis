<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Entity;
use Drago;

/**
 * Settings entity.
 */
class Settings extends Drago\Database\Entity
{
	/**
	 * @var string
	 */
	public $website;

	/**
	 * @var string
	 */
	public $description;

}
