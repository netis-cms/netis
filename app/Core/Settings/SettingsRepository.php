<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Database;
use Exception;
use RuntimeException;


/**
 * This class handles fetching the settings as key-value pairs from the database.
 */
#[Table(SettingsEntity::Table)]
class SettingsRepository
{
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}


	/**
	 * Fetches all settings from the database as key-value pairs.
	 */
	public function getSettings(): array
	{
		try {
			// Fetch settings from the database using the read method, and map them as key-value pairs.
			return $this->read('*')->fetchPairs(SettingsEntity::ColumnName, SettingsEntity::ColumnValue);

		} catch (Exception $e) {
			// Handle potential errors (e.g., database connection or query failure).
			// Log the error or handle it as needed.
			// For example: throw a custom exception or return an empty array.

			throw new RuntimeException('Failed to fetch settings from the database: ' . $e->getMessage(), 0, $e);
		}
	}
}
