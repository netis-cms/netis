<?php

declare(strict_types=1);

namespace App\Modules\Install\Presenters\Control\Database;

use App\Modules\Install\Services\Steps;
use dibi;
use Drago\Application\UI\Alert;
use Drago\Application\UI\ExtraControl;
use Drago\Parameters\Parameters;
use Drago\Utils\ExtraArrayHash;
use Nette\Application\UI\Form;
use Nette\DI\Config\Loader;
use Throwable;


/**
 * Database server settings.
 * @property-read DatabaseTemplate $template
 */
final class DatabaseControl extends ExtraControl
{
	public function __construct(
		private Steps $steps,
		private Loader $loader,
		private Parameters $dirs,
	) {
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/Database.latte');
		$template->setTranslator($this->translator);
		$template->form = $this['database'];
		$template->render();
	}


	protected function createComponentDatabase(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText('host', 'Database server')
			->setRequired();

		$form->addText('user', 'Username')
			->setRequired();

		$form->addText('password', 'Password')
			->setRequired();

		$form->addText('database', 'Database name')
			->setRequired();

		$form->addSubmit('send');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form, ExtraArrayHash $data): void
	{
		try {

			// Check database connection.
			if (dibi::connect($data->toArray())->isConnected()) {

				// Parameters for generate config neon file.
				$arr = ['extensions' => [
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

				// Generate and save the configuration file.
				$this->loader->save($arr, $this->dirs->getAppDir() . '/Services/db.neon');

				// Save the installation step.
				$this->steps->setStep(2);
				$this->getPresenter()->flashMessage(
					'Database settings were successful.', Alert::SUCCESS
				);
			}

		} catch (Throwable $e) {
			if ($e->getCode()) {
				$message = match ($e->getCode()) {
					0 => 'The server does not support the selected database type.',
					1 => 'Database cannot be uploaded, table names conflict.',
					1044 => 'Access denied, check database settings.',
					1045 => 'Failed to verify database username or password.',
					1049 => 'The database name does not exist.',
					1050 => 'Table already exists.',
					2002 => 'The database server did not respond.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}

			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}
}
