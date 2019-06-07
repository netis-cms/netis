<?php

declare(strict_types = 1);

namespace Module\Install;

use App\BasePresenter;
use Drago\Localization\Translator;
use Module\Install\Control\Account;
use Module\Install\Control\Database;
use Module\Install\Control\Tables;
use Module\Install\Control\Website;
use Module\Install\Service\Steps;


/**
 * Installation and configuration application
 */
final class InstallPresenter extends BasePresenter
{
	/**
	 * @var Steps
	 * @inject
	 */
	public $steps;

	/**
	 * @var Database
	 * @inject
	 */
	public $database;

	/**
	 * @var Tables
	 * @inject
	 */
	public $tables;

	/**
	 * @var Website
	 * @inject
	 */
	public $website;

	/**
	 * @var Account
	 * @inject
	 */
	public $account;


	public function getTranslator(): Translator
	{
		$path = __DIR__ . '/../locale/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		// The current language parameter.
		$this->template->lang = $this->lang;

		// Translation for Templates.
		$this->template->setTranslator($this->getTranslator());

		// Current step in template.
		$step = $this->steps->cache->load(Steps::STEP);
		$this->template->step = $step ? $step['step'] : 0;
	}


	public function renderDefault(): void
	{
		if ($this->isAjax()) {
			$this->redrawControl('install');
		}
	}


	/**
	 * Run install application.
	 */
	public function handleRun(): void
	{
		$this->steps->cache->save(Steps::STEP, ['step' => 1]);
	}


	protected function createComponentDatabase(): Database
	{
		$control = $this->database;
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	protected function createComponentTables(): Tables
	{
		$control = $this->tables;
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	protected function createComponentWebsite(): Website
	{
		$control = $this->website;
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	protected function createComponentAccount(): Account
	{
		$control = $this->account;
		$control->setTranslator($this->getTranslator());
		return $control;
	}
}
