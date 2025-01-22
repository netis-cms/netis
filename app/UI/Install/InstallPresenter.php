<?php

declare(strict_types=1);

namespace App\UI\Install;

use App\Core\User\UserSingUpFactory;
use App\UI\Install\Factory\DatabaseFactory;
use App\UI\Install\Factory\TablesFactory;
use App\UI\Install\Factory\WebsiteFactory;
use Drago\Application\UI\Alert;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Neon\Exception;
use Throwable;


/**
 * Installation and configuration application.
 * @property InstallTemplate $template
 */
final class InstallPresenter extends Presenter
{
	use TranslatorAdapter;

	public function __construct(
		private readonly Steps $steps,
		private readonly DatabaseFactory $databaseFactory,
		private readonly TablesFactory $tablesFactory,
		private readonly WebsiteFactory $websiteFactory,
		private readonly UserSingUpFactory $userSingUpFactory,
	) {
		parent::__construct();
	}


	/**
	 * Prepare the installation step before rendering.
	 * @throws Throwable
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$step = $this->steps->getStep();
		$this->template->step = $step ?? 0;
	}


	/**
	 * Get the translator instance for language support.
	 * @throws Exception
	 */
	public function getTranslator(): Translator
	{
		$translator = $this->translator;
		$translator->setCustomTranslate(__DIR__ . '/Translate/', $this->lang);
		return $translator;
	}


	/**
	 * Render default installation page.
	 */
	public function renderDefault(): void
	{
		$this->redrawControl('install');
	}


	/**
	 * Handle the installation process start.
	 * Run the installation and set the first step.
	 */
	public function handleRun(): void
	{
		$this->steps->setStep(1);
	}


	/**
	 * Create and return the database configuration form.
	 */
	protected function createComponentDatabase(): Form
	{
		$form = $this->databaseFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Database settings were successful.', Alert::Success);
		};
		return $form;
	}


	/**
	 * Create and return the table configuration form.
	 */
	protected function createComponentTables(): Form
	{
		$form = $this->tablesFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Database installation was successful.', Alert::Success);
		};
		return $form;
	}


	/**
	 * Create and return the website configuration form.
	 */
	protected function createComponentWebsite(): Form
	{
		$form = $this->websiteFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Site settings successful.', Alert::Success);
		};
		return $form;
	}


	/**
	 * Create and return the account creation form for the administrator.
	 */
	protected function createComponentAccount(): Form
	{
		$form = $this->userSingUpFactory;
		$form->userId = 1;
		$form->roleId = 3;
		$form = $form->create();
		$form->onSuccess[] = function () {
			$this->steps->setStep(5);
			$this->flashMessage('Account administrator registration successful.', Alert::Success);
		};

		return $form;
	}
}
