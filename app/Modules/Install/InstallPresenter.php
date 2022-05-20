<?php

declare(strict_types=1);

namespace App\Modules\Install;

use App\Modules\Install\Control\Account\AccountControl;
use App\Modules\Install\Control\Database\DatabaseControl;
use App\Modules\Install\Control\Tables\TablesControl;
use App\Modules\Install\Control\Website\WebsiteControl;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;
use Throwable;


/**
 * Installation and configuration application.
 * @property-read InstallTemplate $template
 */
final class InstallPresenter extends Presenter
{
	use TranslatorAdapter;

	public function __construct(
		private Steps $steps,
		private DatabaseControl $databaseControl,
		private TablesControl $tablesControl,
		private WebsiteControl $websiteControl,
		private AccountControl $accountControl,
	) {
		parent::__construct();
	}


	/**
	 * @throws Throwable
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$step = $this->steps->getStep();
		$this->template->step = $step ?? 0;
	}


	public function getTranslator(): Translator
	{
		$translator = $this->translator;
		$translator->setCustomTranslate(__DIR__ . '/locales/', $this->lang);
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
		$this->steps->setStep(1);
	}


	protected function createComponentDatabase(): DatabaseControl
	{
		$control = $this->databaseControl;
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentTables(): TablesControl
	{
		$control = $this->tablesControl;
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentWebsite(): WebsiteControl
	{
		$control = $this->websiteControl;
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentAccount(): AccountControl
	{
		$control = $this->accountControl;
		$control->translator = $this->getTranslator();
		return $control;
	}
}
