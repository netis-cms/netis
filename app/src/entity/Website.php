<?php

declare(strict_types = 1);

namespace Entity;

use Drago;

/**
 * Website settings entity.
 */
class WebsiteEntity extends Drago\Database\Entity
{
	const TABLE = ':prefix:settings';

	/** @var string  */
	public $website;

	/** @var string  */
	public $description;
}
