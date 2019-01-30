<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Module\Install\Control;

use Drago;
use Drago\Directory;
use Drago\Http;

use Dibi;
use dibi as dibiDatabase;
use Module\Install\Service;

use Nette\Application\UI;
use Nette\DI\Config;
use Nette\Utils;

/**
 * Database server settings.
 */
final class Database extends Drago\Application\UI\Control
{
	use Drago\Application\UI\Factory;
	use Drago\Localization\TranslateControl;

	/**
	 * @var Config\Loader
	 */
	private $loader;

	/**
	 * @var Http\Sessions
	 */
	private $sessions;

	/**
	 * @var Service\Steps
	 */
	private $steps;

	/**
	 * @var Directory\Dirs
	 */
	private $dirs;

	public function __construct(
		Config\Loader $loader,
		Http\Sessions $sessions,
		Service\Steps $steps,
		Directory\Dirs $dirs)
	{
		parent::__construct();
		$this->loader = $loader;
		$this->sessions = $sessions;
		$this->steps = $steps;
		$this->dirs = $dirs;
	}

	public function render()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.database.latte');
		$template->setTranslator($this->translation);
		$template->form = $this['database'];
		$template->render();
	}

	/**
	 * @return UI\Form
	 */
	protected function createComponentDatabase()
	{
		$form = $this->createForm();
		$form->setTranslator($this->translation);

		$form->addText('host', 'form.host')
			->setRequired('form.required');

		$form->addText('user', 'form.user')
			->setRequired('form.required');

		$form->addText('password', 'form.password')
			->setRequired('form.required');

		$form->addText('database', 'form.name.db')
			->setRequired('form.required');

		$form->addText('prefix', 'form.prefix')
			->setAttribute('placeholder', 'ns_');

		// Database drivers.
		$drivers = [
			'mysql'  => 'MySQL',
			'mysqli' => 'MySQLi'
		];

		$form->addSelect('driver', 'form.driver', $drivers)
			->setRequired();

		$form->addSubmit('send', 'form.send.db');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}

	public function success(UI\Form $form)
	{
		$values = $form->values;
		try {
			// Check database connection.
			if (dibiDatabase::connect($values)) {

				// Parameters for generate config neon file.
				$arr = ['extensions' => [
						'dibi' => 'Dibi\Bridges\Nette\DibiExtension22'
					], 'dibi' => [
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
				Utils\FileSystem::delete($this->dirs->getTempDir() . '/cache/Nette.Configurator');

				// Save the installation step.
				$this->steps->cache->save(Service\Steps::STEP, ['step' => 2]);
				$this->flashMessage('message.db', 'success');

				// Save db prefix.
				if ($values->prefix) {
					$this->sessions->getSessionSection()->prefix = $values->prefix;
				}

			}

		} catch (Dibi\Exception $e) {
			switch ($e->getCode()) {
				case 0:    $message = 'form.driver.error'; break;
				case 1044: $message = 'form.access.error'; break;
				case 1045: $message = 'form.auth.error'; break;
				case 1049: $message = 'form.name.db.error'; break;
				case 2002: $message = 'form.host.error'; break;
			}
			$form->addError($message);
			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}

}
