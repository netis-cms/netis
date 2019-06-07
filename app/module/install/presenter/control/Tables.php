<?php

declare(strict_types = 1);

namespace Module\Install\Control;

use Drago\Application\UI\Control;
use Drago\Application\UI\Factory;
use Drago\Http\Sessions;
use Drago\Localization\TranslateControl;
use Module\Install\Service\Query;
use Module\Install\Service\Steps;
use Nette\Application\UI\Form;
use Exception;


/**
 * Install database tables.
 */
final class Tables extends Control
{
	use Factory;
	use TranslateControl;

	/** @var Sessions */
	private $sessions;

	/** @var Steps */
	private $steps;

	/** @var Query */
	private $query;


	public function __construct(Sessions $sessions, Steps $steps, Query $query)
	{
		$this->sessions = $sessions;
		$this->steps = $steps;
		$this->query = $query;
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.tables.latte');
		$template->setTranslator($this->getTranslator());
		$template->form = $this['tables'];
		$template->render();
	}


	public function createComponentTables(): Form
	{
		$form = $this->createForm();
		$form->setTranslator($this->getTranslator());
		$form->addSubmit('send', 'form.send.tables');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form): void
	{
		$prefix = $this->sessions->getSessionSection()->prefix;
		$databaseTable = [
			'menu' => $prefix . 'menu',
			'menu_category' => $prefix . 'menu_category',
			'settings' => $prefix . 'settings',
			'users' => $prefix . 'users',
		];

		try {
			foreach ($databaseTable as $check) {
				if (!$this->query->isTable($check)) {
					continue;
				}
			}
			$this->query->addTable('
				CREATE TABLE [' . $databaseTable['menu_category'] . '](
				[categoryId] int(11) unsigned NOT NULL AUTO_INCREMENT,
				[category] varchar(30) NOT NULL,
				PRIMARY KEY (categoryId))
				ENGINE=InnoDB DEFAULT CHARSET=UTF8');

			$menuCategory = [
				['categoryId' => 1, 'category' => 'category.system'],
			];

			foreach ($menuCategory as $rows) {
				$this->query->addRecord($databaseTable['menu_category'], $rows);
			}

			$this->query->addTable('
				CREATE TABLE [' . $databaseTable['menu'] . '](
				[menuId] int(11) unsigned NOT NULL AUTO_INCREMENT,
				[categoryId] int(11) unsigned NOT NULL,
				[link] char(30) NOT NULL,
				[name] char(30) NOT NULL,
				PRIMARY KEY (menuId),
				KEY [category] (categoryId),
				CONSTRAINT [enu_ibfk_' . rand(5, 999) . '] FOREIGN KEY (categoryId) REFERENCES [' . $prefix . 'menu_category] (categoryId))
				ENGINE=InnoDB DEFAULT CHARSET=UTF8');

			$menu = [
				['menuId' => 1, 'link' => ':Admin:Admin:main', 'name' => 'menu.admin', 'categoryId' => 1],
				['menuId' => 2, 'link' => ':Admin:Settings:web', 'name' => 'menu.settings', 'categoryId' => 1],
			];

			foreach ($menu as $rows) {
				$this->query->addRecord($databaseTable['menu'], $rows);
			}

			$this->query->addTable('
				CREATE TABLE [' . $databaseTable['settings'] . '](
				[name] varchar(100) NOT NULL,
				[value] varchar(255) NOT NULL)
				ENGINE=InnoDB DEFAULT CHARSET=UTF8'
			);

			$this->query->addTable('
				CREATE TABLE [' . $databaseTable['users'] . '](
				[userId] int(11) unsigned NOT NULL AUTO_INCREMENT,
				[realname] varchar(50) NOT NULL,
				[email] varchar(50) NOT NULL,
				[password] char(60) NOT NULL,
				PRIMARY KEY (userId))
				ENGINE=InnoDB DEFAULT CHARSET=UTF8'
			);

			// Save the installation step.
			$this->steps->cache->save(Steps::STEP, ['step' => 3]);
			$this->presenter->flashMessage('message.tables', 'success');

		} catch (Exception $e) {
			if ($e->getCode()) {
				$form->addError('form.error.' . $e->getCode());
			}

			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}
}
