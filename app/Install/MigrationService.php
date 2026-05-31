<?php

declare(strict_types=1);

namespace App\Install;

use Dibi\Connection;
use InvalidArgumentException;
use RuntimeException;
use Throwable;
use Tracy\Debugger;


final readonly class MigrationService
{
	public function __construct(
		private Connection $db,
		private string $migrationsPath,
	) {
	}


	/**
	 * Returns a list of migration files.
	 * @return list<string>
	 */
	public function getFiles(): array
	{
		$files = glob($this->migrationsPath . '/*.sql');
		if ($files === false) {
			return [];
		}

		$files = array_map(
			static fn(string $file): string => basename($file),
			$files,
		);

		sort($files);
		return $files;
	}


	/**
	 * Runs one migration.
	 * @return array{
	 *     file: string,
	 *     status: 'success'
	 * }|array{
	 *     file: string,
	 *     status: 'error',
	 *     message: string
	 * }
	 */
	public function run(string $file): array
	{
		if (!preg_match('~^[a-zA-Z0-9._-]+\.sql$~', $file)) {
			throw new InvalidArgumentException('Invalid file name.');
		}

		$fullPath = $this->migrationsPath . '/' . $file;
		if (!is_file($fullPath)) {
			throw new RuntimeException('Migration file not found.');
		}

		try {
			$this->db->loadFile($fullPath);
			return [
				'file' => $file,
				'status' => 'success',
			];

		} catch (Throwable $t) {
			Debugger::log($t);
			return [
				'file' => $file,
				'status' => 'error',
				'message' => $t->getMessage(),
			];
		}
	}
}
