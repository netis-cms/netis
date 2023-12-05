<?php

declare(strict_types=1);

namespace App\Modules\Install;

use App\Modules\BaseFactory;
use Dibi\Connection;
use Nette\Application\UI\Form;
use Throwable;


/**
 * Install database tables.
 */
final class TablesFactory
{
	public function __construct(
		private readonly Connection $db,
		private readonly Steps $steps,
		private readonly BaseFactory $baseFactory,
	) {
	}


	public function create(): Form
	{
		$form = $this->baseFactory->create();
		$form->addSubmit('send', 'Upload database');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form): void
	{
		try {

			// Import SQL dump from file.
			$this->db->loadFile(__DIR__ . '/db.sql');

			// Save the installation step.
			$this->steps->setStep(3);

		} catch (Throwable $t) {
			if ($t->getCode()) {
				$message = match ($t->getCode()) {
					1050 => 'Some table names already exist in the database.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
		}
	}
}
