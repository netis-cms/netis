<?php

declare(strict_types=1);

namespace Module\Install\Control;

use dibi;
use Drago\Localization\Translator;
use Drago\Parameters\Parameters;
use Drago\Utils\ExtraArrayHash;
use Module\Install\Service\Steps;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\DI\Config\Loader;
use Nette\Utils\FileSystem;
use Tracy\Debugger;


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
		/** @var Template $template */
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.database.latte');
		$template->setTranslator($this->translator);
		$template->form = $this['database'];
		$template->render();
	}


	protected function createComponentDatabase(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText('host', 'form.host')
			->setRequired('form.required');

		$form->addText('user', 'form.user')
			->setRequired('form.required');

		$form->addText('password', 'form.password')
			->setRequired('form.required');

		$form->addText('database', 'form.name.db')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.db');
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
				$this->loader->save($arr, $this->dirs->getAppDir() . '/src/db.neon');

				// Removing the old cache for updating the system container.
				FileSystem::delete($this->dirs->getTempDir() . '/cache/Nette.Configurator');

				// Save the installation step.
				$this->steps->cache->save(Steps::STEP, ['step' => 2]);
				$this->presenter->flashMessage('message.db', 'success');
			}

		} catch (\Exception $e) {
			if ($e->getCode()) {
				$form->addError('form.error.' . $e->getCode());
			}

			if ($this->presenter->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}
}
