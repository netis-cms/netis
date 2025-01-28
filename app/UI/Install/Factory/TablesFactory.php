<?php

declare(strict_types=1);

namespace App\UI\Install\Factory;

use App\Core\Factory;
use App\UI\Install\Steps;
use Dibi\Connection;
use Nette\Application\UI\Form;
use Throwable;


/**
 * Install database tables by loading an SQL dump file.
 */
final readonly class TablesFactory
{
	public function __construct(
		private Connection $db,
		private Steps $steps,
		private Factory $factory,
	) {
	}


	/**
	 * Creates the form for uploading database tables.
	 *
	 * @return Form The form for uploading database tables.
	 */
	public function create(): Form
	{
		$form = $this->factory->create();

		// Add submit button for database upload
		$form->addSubmit('send', 'Upload database');

		// Success handler for the form
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}


	/**
	 * Handles the success of the database upload form.
	 * This method attempts to import an SQL dump file into the database.
	 *
	 * @param Form $form The form object.
	 * @return void
	 */
	public function success(Form $form): void
	{
		try {
			// Import SQL dump file into the database
			$this->db->loadFile(__DIR__ . '/db.sql');

			// Save the installation step after successful import
			$this->steps->setStep(3);

		} catch (Throwable $t) {
			// Handle errors during SQL import
			if ($t->getCode()) {
				$message = match ($t->getCode()) {
					1050 => 'Some table names already exist in the database.',
					default => 'Unknown status code.',
				};

				// Add error message to form
				$form->addError($message);
			}
		}
	}
}
