<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Drago\Attr\AttributeDetectionException;
use Drago\Attr\From;
use Drago\Database\Database;


#[From(SettingsEntity::Table)]
class SettingsRepository extends Database
{
	/**
	 * @throws AttributeDetectionException
	 */
	public function getSettings(): array
	{
		return $this->read()
			->fetchPairs(SettingsEntity::ColumnName, SettingsEntity::ColumnValue);
	}
}
