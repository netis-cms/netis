<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Module\Install;

use Drago;
use Nette;

/**
 * Installation and configuration application.
 */
final class InstallPresenter extends Nette\Application\UI\Presenter
{
	use Drago\Localization\Locale;

	/**
	 * @var Service\Steps
	 * @inject
	 */
	public $steps;

	/**
	 * @var Control\Database
	 * @inject
	 */
	public $database;

	/**
	 * @var Control\Tables
	 * @inject
	 */
	public $tables;

	/**
	 * @var Control\Website
	 * @inject
	 */
	public $website;

	/**
	 * @var Control\Account
	 * @inject
	 */
	public $account;

	/**
	 * @return Drago\Localization\Translator
	 */
	public function translator()
	{
		$path = __DIR__ . '/../locale/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->setTranslator($this->translator());
		$this->template->lang = $this->lang;
		$step = $this->steps->cache->load(Service\Steps::STEP);
		$this->template->step = $step ? $step['step'] : 0;
	}

	public function renderDefault()
	{
		if ($this->isAjax()) {
			$this->redrawControl('install');
		}
	}

	/**
	 * Run the installation.
	 */
	public function handleRun()
	{
		$this->steps->cache->save(Service\Steps::STEP, ['step' => 1]);
	}

	/**
	 * @return Control\Database
	 */
	protected function createComponentDatabase()
	{
		$control = $this->database;
		$control->setTranslator($this->translator());
		return $control;
	}

	/**
	 * @return Control\Tables
	 */
	protected function createComponentTables()
	{
		$control = $this->tables;
		$control->setTranslator($this->translator());
		return $control;
	}

	/**
	 * @return Control\Website
	 */
	protected function createComponentWebsite()
	{
		$control = $this->website;
		$control->setTranslator($this->translator());
		return $control;
	}

	/**
	 * @return Control\Account
	 */
	protected function createComponentAccount()
	{
		$control = $this->account;
		$control->setTranslator($this->translator());
		return $control;
	}

}
