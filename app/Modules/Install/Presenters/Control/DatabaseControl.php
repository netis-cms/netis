<?php

declare(strict_types=1);

namespace App\Modules\Install\Presenters\Control;

use App\Modules\Install\Services\Steps;
use dibi;
use Drago\Localization\Translator;
use Drago\Parameters\Parameters;
use Drago\Utils\ExtraArrayHash;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\DI\Config\Loader;
use Nette\InvalidStateException;


/**
 * Database server settings.
 */
final class DatabaseControl extends Control
{
	public function __construct(
		private Translator $translator,
		private Steps $steps,
		private Loader $loader,
		private Parameters $dirs,
	) {
	}


	public function render(): void
	{
		if ($this->template instanceof Template) {
			$template = $this->template;
			$template->setFile(__DIR__ . '/../templates/Control.database.latte');
			$template->setTranslator($this->translator);
			$template->form = $this['database'];
			$template->render();
		} else {
			throw new InvalidStateException('Control is without template.');
		}
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

				// Removing the old cache for updating the system container.
				//FileSystem::delete($this->dirs->getTempDir() . '/cache/Nette.Configurator');

				// Save the installation step.
				$this->steps->cache->save(Steps::STEP, ['step' => 2]);
				$this->presenter->flashMessage('Database settings were successful.', 'success');
			}

		} catch (\Throwable $e) {
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

			if ($this->presenter->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}
}
