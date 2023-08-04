<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Dibi\Connection;
use Drago\Application\UI\ExtraTemplate;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;
use Throwable;


/**
 * Install database tables.
 * @property-read ExtraTemplate $template
 */
final class TablesFactory
{
	public function __construct(
		private readonly Connection $db,
		private readonly Steps $steps,
		private readonly Translator $translator,
	) {
	}


	public function create(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);
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
