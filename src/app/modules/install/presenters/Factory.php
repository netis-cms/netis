<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Install;

use Nette;
use Nette\Utils;
use Nette\DI\Config;
use Nette\Security;
use Nette\Application\UI;

use Dibi;
use dibi as DibiDatabase;
use Install\Service;
use Exception;

use Drago\Application;
use Drago\Directory;
use Drago\Http;

/**
 * Install factory.
 */
class Factory
{
	use Nette\SmartObject;

	/**
	 * @var Service\Query
	 */
	private $query;

	/**
	 * @var Service\Steps
	 */
	private $steps;

	/**
	 * @var Directory\Dirs
	 */
	private $dirs;

	/**
	 * @var Config\Loader
	 */
	private $loader;

	/**
	 * @var Http\Sessions
	 */
	private $sessions;

	/**
	 * @var Application\UI\Factory
	 */
	private $factory;

	public function __construct(
		Service\Query $query,
		Service\Steps $steps,
		Directory\Dirs $dirs,
		Config\Loader $loader,
		Http\Sessions $sessions,
		Application\UI\Factory $factory)
	{
		$this->query = $query;
		$this->steps = $steps;
		$this->dirs = $dirs;
		$this->loader = $loader;
		$this->sessions = $sessions;
		$this->factory = $factory;
	}

	/**
	 * @param array
	 * @param callable
	 * @return UI\Form
	 */
	public function databaseHostFactory($translator, callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->setTranslator($translator);

		$form->addText('host', 'install.db.host')
			->setRequired('install.form.empty');

		$form->addText('user', 'install.db.user')
			->setRequired('install.form.empty');

		$form->addText('password', 'install.db.pass');

		$form->addText('database', 'install.db.name')
			->setRequired('install.form.empty');

		$form->addText('prefix', 'install.db.prefix')
			->setAttribute('placeholder', 'ns_');

		// Database drivers.
		$drivers = [
			'mysql'  => 'MySQL',
			'mysqli' => 'MySQLi'
		];

		$form->addSelect('driver', 'install.db.driver', $drivers)
			->setRequired();

		$form->addSubmit('send', 'install.db.send');
		$form->onSuccess[] = function (UI\Form $form, $values) use ($onSuccess) {
			try {
				// Check database connection.
				if (DibiDatabase::connect($values)) {

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
					$this->loader->save($arr, $this->dirs->getAppDir() . '/modules/app.db.neon');

					// Removing the old cache for updating the system container.
					Utils\FileSystem::delete($this->dirs->getTempDir() . '/cache/Nette.Configurator');

					// Save the installation step.
					$this->steps->setToCache(Service\Steps::STEP_1, rand(1, 9));

					// Save db prefix.
					if ($values->prefix) {
						$this->sessions->getSessionSection()->prefix = $values->prefix;
					}
				}

			} catch (Dibi\Exception $e) {

				// Server database type error.
				if ($e->getCode() === 0) {
					$form->addError('install.db.driver.catch');

				// Host server not found.
				} elseif ($e->getCode() === 2002) {
					$form->addError('install.db.host.catch');

				// The user or password was not verified.
				} elseif ($e->getCode() === 1045) {
					$form->addError('install.db.auth.catch');

				// The database name was not found
				} elseif ($e->getCode() === 1049) {
					$form->addError('install.db.name.catch');
				}
				return;
			}
			$onSuccess();
		};
		return $form;
	}

	/**
	 * Prefix of database tables.
	 * @return string
	 */
	private function getTablePrefix()
	{
		return $this->sessions->getSessionSection()->prefix;
	}

	/**
	 * @param array
	 * @param callable
	 * @return UI\Form
	 */
	public function dbTablesFactory($translator, callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->setTranslator($translator);

		$form->addSubmit('send', 'install.db.tables.send');
		$form->onSuccess[] = function (UI\Form $form) use ($onSuccess) {

			$prefix = $this->getTablePrefix();
			$databaseTable = [
				'nav'      => $prefix . 'nav',
				'settings' => $prefix . 'settings',
				'users'    => $prefix . 'users',
				'pages'    => $prefix . 'pages',
			];

			try {
				foreach ($databaseTable as $check) {
					if (!$this->query->isExistTable($check)) {
						continue;
					}
				}

				// Table 'nav'
				$this->query->addTable(''
					. 'CREATE TABLE ['.$databaseTable['nav'].']('
					. '[navId] int(11) unsigned NOT NULL AUTO_INCREMENT,'
					. '[link] char(30) NOT NULL,'
					. '[cs] char(30) NOT NULL,'
					. 'PRIMARY KEY (navId))'
					. 'ENGINE=InnoDB DEFAULT CHARSET=UTF8'
				);

				$nav = [
					['navId' => NULL, 'link' => ':Admin:Admin:',    'cs' => 'Administrace'],
					['navId' => NULL, 'link' => ':Admin:Settings:', 'cs' => 'Nastavení webu'],
					['navId' => NULL, 'link' => ':Web:Web:',        'cs' => 'Web'],
				];

				foreach ($nav as $rows) {
					$this->query->addRecord($databaseTable['nav'], $rows);
				}

				// Table 'settings'
				$this->query->addTable(''
					. 'CREATE TABLE ['.$databaseTable['settings'].']('
					. '[name] varchar(100) NOT NULL,'
					. '[value] varchar(255) NOT NULL)'
					. 'ENGINE=InnoDB DEFAULT CHARSET=UTF8'
				);

				// Table 'users'
				$this->query->addTable(''
					. 'CREATE TABLE ['.$databaseTable['users'].']('
					. '[userId] int(11) unsigned NOT NULL AUTO_INCREMENT,'
					. '[realname] varchar(50) NOT NULL,'
					. '[email] varchar(50) NOT NULL,'
					. '[password] char(60) NOT NULL,'
					. 'PRIMARY KEY (userId))'
					. 'ENGINE=InnoDB DEFAULT CHARSET=UTF8'
				);

				// Table 'pages'
				$this->query->addTable(''
					. 'CREATE TABLE ['.$databaseTable['pages'].']('
					. '[pageId] int(11) unsigned NOT NULL AUTO_INCREMENT,'
					. '[name] varchar(120) NOT NULL,'
					. '[slug] varchar(120) NOT NULL,'
					. '[text] text NOT NULL,'
					. 'PRIMARY KEY (pageId))'
					. 'ENGINE=InnoDB DEFAULT CHARSET=UTF8'
				);

				// Save the installation step.
				$this->steps->setToCache(Service\Steps::STEP_2, rand(1, 9));

			} catch (Exception $e) {
				if ($e->getCode() === 1) {
					$form->addError('install.db.table.catch');
				}
				return;
			}
			$onSuccess();
		};
		return $form;
	}

	/**
	 * @param array
	 * @param callable
	 * @return UI\Form
	 */
	public function websiteSettingsFactory($translator, callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->setTranslator($translator);

		$form->addText('website', 'install.web.name')
			->setRequired('install.form.empty');

		$form->addText('description', 'install.web.description')
			->setRequired('install.form.empty');

		$form->addSubmit('send', 'install.web.send');
		$form->onSuccess[] = function (UI\Form $form) use ($onSuccess) {

			$values = $form->getValues();
			$table  = $this->getTablePrefix() . 'settings';
			$settings = [
				['name' => 'website',     'value' => $values->website],
				['name' => 'description', 'value' => $values->description],
			];

			// Insert records into the database.
			foreach ($settings as $rows) {
				$this->query->addRecord($table, $rows);
			}

			// Save the installation step.
			$this->steps->setToCache(Service\Steps::STEP_3, rand(1, 9));
			$onSuccess();
		};
		return $form;
	}

	/**
	 * @param array
	 * @param callable
	 * @return UI\Form
	 */
	public function registrationAccount($translator, callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->setTranslator($translator);

		$form->addText('realname', 'install.acc.name')
			->setRequired('install.form.empty');

		$form->addText('email', 'install.acc.email')
			->setDefaultValue('@')
			->setType('email')
			->setRequired('install.form.empty')
			->addRule(UI\Form::EMAIL, 'install.acc.email.invalid');

		$form->addPassword('password', 'install.acc.pass')
			->setRequired('install.form.empty')
			->addRule(UI\Form::MIN_LENGTH, 'install.acc.pass.min', 6);

		$form->addPassword('verify', 'install.acc.pass.verify')
			->setRequired('install.form.empty')
			->addRule(UI\Form::EQUAL, 'install.acc.pass.verify.invalid', $form['password']);

		$form->addSubmit('send', 'install.acc.send');
		$form->onSuccess[] = function (UI\Form $form) use ($onSuccess) {

			$values = $form->getValues();
			$table  = $this->getTablePrefix() . 'users';

			// Hash password.
			$values->password = Security\Passwords::hash($values->password);

			// Undo unneeded values.
			unset($values->verify, $values->prefix);

			// Insert records into the database.
			$this->query->addRecord($table, $values);

			// Save the installation step.
			$this->steps->setToCache(Service\Steps::STEP_4, rand(1, 9));
			$onSuccess();
		};
		return $form;
	}

}
