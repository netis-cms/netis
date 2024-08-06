<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Dibi\Connection;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Repository;


#[Table(SettingsEntity::Table)]
class SettingsRepository
{
	use Repository;

	public function __construct(
		protected Connection $db,
	) {
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getSettings(): array
	{
		return $this->query()
			->fetchPairs(SettingsEntity::ColumnName, SettingsEntity::ColumnValue);
	}
}
