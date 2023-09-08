<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Application\UI\Alert;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Form;
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
		private readonly Steps $steps,
		private readonly DatabaseFactory $databaseFactory,
		private readonly TablesFactory $tablesFactory,
		private readonly WebsiteFactory $websiteFactory,
		private readonly AccountFactory $accountFactory,
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


	protected function createComponentDatabase(): Form
	{
		$form = $this->databaseFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Database settings were successful.', Alert::Success);
		};
		return $form;
	}


	protected function createComponentTables(): Form
	{
		$form = $this->tablesFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Database installation was successful.', Alert::Success);
		};
		return $form;
	}


	protected function createComponentWebsite(): Form
	{
		$form = $this->websiteFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Site settings successful.', Alert::Success);
		};
		return $form;
	}


	protected function createComponentAccount(): Form
	{
		$form = $this->accountFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Account administrator registration successful.', Alert::Success);
		};
		return $form;
	}
}
