<?php

declare(strict_types = 1);

namespace Module\Install;

use Drago\Localization\Translator;
use Drago\Localization\TranslatorAdapter;
use Module\Install\Control;
use Module\Install\Service\Steps;
use Nette;
use Tracy\Debugger;


/**
 * Installation and configuration application.
 */
final class InstallPresenter extends Nette\Application\UI\Presenter
{
	use TranslatorAdapter;

	/**
	 * @var Steps
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


	public function startup(): void
	{
		parent::startup();
		$mode = Debugger::$productionMode;
		$mode ? $this->setLayout('layout') : $this->setLayout('dev');
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		// Current step in template.
		$step = $this->steps->cache->load(Steps::STEP);
		$this->template->step = $step ? $step['step'] : 0;
	}


	public function getTranslator(): Translator
	{
		$translator = $this->translator;
		$translator->setCustomTranslate(__DIR__ . '/../locale/' . $this->lang . '.ini');
		return $translator;
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


	protected function createComponentDatabase(): Control\Database
	{
		$control = $this->database;
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	protected function createComponentTables(): Control\Tables
	{
		$control = $this->tables;
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	protected function createComponentWebsite(): Control\Website
	{
		$control = $this->website;
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	protected function createComponentAccount(): Control\Account
	{
		$control = $this->account;
		$control->setTranslator($this->getTranslator());
		return $control;
	}
}
