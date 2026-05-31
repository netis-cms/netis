<?php

declare(strict_types=1);

namespace App\Install\Factory;

use App\Install\Steps;
use dibi;
use Nette\Application\UI\Form;
use Nette\DI\Config\Adapters\NeonAdapter;
use Throwable;


/** Database server settings configuration and handling. */
final readonly class DatabaseFactory
{
	public function __construct(
		private Steps $steps,
		private NeonAdapter $neonAdapter,
		private string $configPath,
	) {
	}


	/** Creates the form for database connection settings. */
	public function create(): Form
	{
		$form = new Form;
		$form->addText(DatabaseValues::Host, 'Database server')
			->setHtmlAttribute('autocomplete', 'off')
			->setRequired();

		$form->addText(DatabaseValues::User, 'Username')
			->setHtmlAttribute('autocomplete', 'off')
			->setRequired();

		$form->addText(DatabaseValues::Password, 'Password')
			->setHtmlAttribute('autocomplete', 'off')
			->setRequired();

		$form->addText(DatabaseValues::Database, 'Database name')
			->setHtmlAttribute('autocomplete', 'off')
			->setRequired();

		$form->addSubmit('send', 'Connection test');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	/** Handles the success of the database form submission. */
	public function success(Form $form, DatabaseValues $data): void
	{
		try {
			if (dibi::connect($data->toArray())->isConnected()) {
				$arr = [
					'extensions' => [
						'dibi' => 'Dibi\Bridges\Nette\DibiExtension3',
					],
					'dibi' => [
						'host' => $data->host,
						'username' => $data->user,
						'password' => $data->password,
						'database' => $data->database,
						'driver' => 'mysqli',
						'lazy' => true,
					],
				];

				$content = $this->neonAdapter->dump($arr);
				$filePath = $this->configPath . '/db.neon';
				if (file_put_contents($filePath, $content) === false) {
					throw new \RuntimeException('Cannot write to file: ' . $filePath);
				}

				$this->steps->setStep(2);
			}

		} catch (Throwable $t) {
			if ($t->getCode()) {
				$message = match ($t->getCode()) {
					1044 => 'Access denied, check database settings.',
					1045 => 'Failed to verify database username or password.',
					1049 => 'The database name does not exist.',
					2002 => 'The database server did not respond.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
		}
	}
}
