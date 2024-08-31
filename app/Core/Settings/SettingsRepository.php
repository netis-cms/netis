<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Dibi\Connection;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;


#[Table(SettingsEntity::Table)]
class SettingsRepository
{
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getSettings(): array
	{
		return $this->read('*')
			->fetchPairs(SettingsEntity::ColumnName, SettingsEntity::ColumnValue);
	}
}
