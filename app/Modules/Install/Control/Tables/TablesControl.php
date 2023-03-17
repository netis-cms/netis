<?php

declare(strict_types=1);

namespace App\Modules\Install\Control\Tables;

use App\Modules\Install\Steps;
use Dibi\Connection;
use Drago\Application\UI\Alert;
use Drago\Application\UI\ExtraControl;
use Drago\Application\UI\ExtraTemplate;
use Nette\Application\UI\Form;
use Throwable;


/**
 * Install database tables.
 * @property-read ExtraTemplate $template
 */
final class TablesControl extends ExtraControl
{
	public function __construct(
		private Connection $db,
		private Steps $steps,
	) {
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/Tables.latte');
		$template->setTranslator($this->translator);
		$template->render();
	}


	public function createComponentTables(): Form
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
			$this->getPresenter()->flashMessage(
				'Database installation was successful.',
				Alert::SUCCESS,
			);

		} catch (Throwable $e) {
			if ($e->getCode()) {
				$message = match ($e->getCode()) {
					1050 => 'Some table names already exist in the database.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
		}
	}
}
