<?php

declare(strict_types = 1);

namespace Repository;

use Drago\Database;
use Entity\SettingsEntity;


class SettingsRepository extends Database\Connect
{
	use Database\Repository;

	/** @var string table name */
	private $table = SettingsEntity::TABLE;
}
