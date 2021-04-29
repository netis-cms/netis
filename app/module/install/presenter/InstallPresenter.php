<?php

declare(strict_types=1);

namespace Module\Install;

use Drago\Localization\Translator;
use Drago\Localization\TranslatorAdapter;
use Module\Install\Control\AccountControl;
use Module\Install\Control\DatabaseControl;
use Module\Install\Control\TablesControl;
use Module\Install\Control\WebsiteControl;
use Module\Install\Service\Steps;
use Nette\Application\UI\Presenter;
use Throwable;
use Tracy\Debugger;


/**
 * Installation and configuration application.
 */
final class InstallPresenter extends Presenter
{
	use TranslatorAdapter;

	public function __construct(
		private Steps $steps,
		private AccountControl $accountControl,
		private DatabaseControl $databaseControl,
		private TablesControl $tablesControl,
		private WebsiteControl $websiteControl,
	) {
		parent::__construct();
	}


	/**
	 * @throws Throwable
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$step = $this->steps->cache->load(Steps::STEP);
		$this->template->step = $step ? $step['step'] : 0;
		$this->template->mode = Debugger::$productionMode;
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


	protected function createComponentAccount(): AccountControl
	{
		return $this->accountControl;
	}


	protected function createComponentDatabase(): DatabaseControl
	{
		return $this->databaseControl;
	}


	protected function createComponentTables(): TablesControl
	{
		return $this->tablesControl;
	}


	protected function createComponentWebsite(): WebsiteControl
	{
		return $this->websiteControl;
	}
}
