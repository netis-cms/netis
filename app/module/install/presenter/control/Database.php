<?php

declare(strict_types = 1);

namespace Module\Install\Control;

use dibi;
use Drago\Application\UI\Control;
use Drago\Application\UI\Factory;
use Drago\Http\Sessions;
use Drago\Localization\TranslateControl;
use Drago\Parameters\Directory;
use Module\Install\Service\Steps;
use Nette\Application\UI\Form;
use Nette\DI\Config\Loader;
use Nette\Utils\FileSystem;
use Tracy\Debugger;


/**
 * Database server settings.
 */
final class Database extends Control
{
	use Factory;
	use TranslateControl;

	/** @var Loader */
	private $loader;

	/** @var Sessions */
	private $sessions;

	/** @var Steps */
	private $steps;

	/** @var Directory */
	private $dirs;


	public function __construct(Loader $loader, Sessions $sessions, Steps $steps, Directory $dirs)
	{
		$this->loader = $loader;
		$this->sessions = $sessions;
		$this->steps = $steps;
		$this->dirs = $dirs;
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.database.latte');
		$template->setTranslator($this->getTranslator());
		$template->form = $this['database'];
		$template->render();
	}


	protected function createComponentDatabase(): Form
	{
		$form = $this->createForm();
		$form->setTranslator($this->getTranslator());

		$form->addText('host', 'form.host')
			->setRequired('form.required');

		$form->addText('user', 'form.user')
			->setRequired('form.required');

		$form->addText('password', 'form.password')
			->setRequired('form.required');

		$form->addText('database', 'form.name.db')
			->setRequired('form.required');

		$form->addText('prefix', 'form.prefix')
			->setHtmlAttribute('placeholder', 'ns_');

		// Database drivers.
		$drivers = [
			'mysqli' => 'MySQLi'
		];

		$form->addSelect('driver', 'form.driver', $drivers)
			->setRequired();

		$form->addSubmit('send', 'form.send.db');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form): void
	{
		$values = $form->values;
		try {

			// Check database connection.
			if (dibi::connect((array) $values)) {

				// Parameters for generate config neon file.
				$arr = ['extensions' => [
					'dibi' => 'Dibi\Bridges\Nette\DibiExtension22'
				],
					'dibi' => [
						'host' => $values->host,
						'username' => $values->user,
						'password' => $values->password,
						'database' => $values->database,
						'driver' => $values->driver,
						'lazy' => TRUE,
						'substitutes' => [
							'prefix' => $values->prefix
						]
					]
				];

				// Generate and save the configuration file.
				$this->loader->save($arr, $this->dirs->getAppDir() . '/src/db.neon');

				// Removing the old cache for updating the system container.
				FileSystem::delete($this->dirs->getTempDir() . '/cache/Nette.Configurator');

				// Save the installation step.
				$this->steps->cache->save(Steps::STEP, ['step' => 2]);
				//$this->presenter->flashMessage('message.db', 'success');

				// Save db prefix.
				if ($values->prefix) {
					$this->sessions->getSessionSection()->prefix = $values->prefix;
				}

			}

		} catch (Dibi\Exception $e) {
			if ($e->getCode()) {
				$form->addError('form.error.' . $e->getCode());
			}

			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}
}
