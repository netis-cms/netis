<?php

declare(strict_types=1);

namespace App\UI\Install\Factory;

use App\Core\Factory;
use App\UI\Install\Steps;
use dibi;
use Drago\Parameters\Parameters;
use Nette\Application\UI\Form;
use Nette\DI\Config\Adapters\NeonAdapter;
use Throwable;


/**
 * Database server settings configuration and handling.
 */
final class DatabaseFactory
{
	public function __construct(
		private readonly Steps $steps,
		private readonly NeonAdapter $neonAdapter,
		private readonly Parameters $dirs,
		private readonly Factory $factory,
	) {
	}


	/**
	 * Creates the form for database connection settings.
	 *
	 * @return Form The database configuration form.
	 */
	public function create(): Form
	{
		$form = $this->factory->create();

		// Add form fields for database settings
		$form->addText(DatabaseData::Host, 'Database server')
			->setRequired();

		$form->addText(DatabaseData::User, 'Username')
			->setRequired();

		$form->addText(DatabaseData::Password, 'Password')
			->setRequired();

		$form->addText(DatabaseData::Database, 'Database name')
			->setRequired();

		// Submit button
		$form->addSubmit('send', 'Connection test');

		// Success handler for the form
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}


	/**
	 * Handles the success of the database form submission.
	 *
	 * This method tries to establish a database connection and generate a neon configuration file.
	 *
	 * @param Form $form The form object.
	 * @param DatabaseData $data The database data submitted by the user.
	 * @return void
	 */
	public function success(Form $form, DatabaseData $data): void
	{
		try {
			// Check if the database connection is successful
			if (dibi::connect($data->toArray())->isConnected()) {

				// Prepare parameters for generating the neon config file
				$arr = [
					'extensions' => [
						'dibi' => 'Dibi\Bridges\Nette\DibiExtension22',
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

				// Generate and save the neon configuration file
				$content = $this->neonAdapter->dump($arr);
				$file = fopen($this->dirs->appDir . '/Core/db.neon', 'w');
				fwrite($file, $content);

				// Save the installation step to move to the next stage
				$this->steps->setStep(2);
			}

		} catch (Throwable $t) {
			// Handle database connection errors
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
