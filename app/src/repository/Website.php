<?php

declare(strict_types = 1);

namespace Repository;

use Drago\Database\Connection;
use Drago\Database\Repository;
use Entity\WebsiteEntity;


/**
 * Website settings repository.
 */
class WebsiteRepository extends Connection
{
	use Repository;

	/** @var string table name */
	private $table = WebsiteEntity::TABLE;
}
