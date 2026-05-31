<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Database;
use RuntimeException;


/** Repository for settings key-value pairs. */
#[Table(SettingsEntity::Table)]
class SettingsRepository
{
	/** @use Database<SettingsEntity> */
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}


	/**
	 * Fetches all settings from the database as key-value pairs.
	 * @return array<string, string>
	 */
	public function getSettings(): array
	{
		try {
			return $this->read('*')
				->fetchPairs(SettingsEntity::ColumnName, SettingsEntity::ColumnValue);

		} catch (\Throwable $e) {
			throw new RuntimeException('Failed to fetch settings from the database: ' . $e->getMessage(), 0, $e);
		}
	}
}
