<?php

declare(strict_types=1);

namespace App\Install;

use App\Core\Permission\Users\UsersRolesEntity;
use App\Core\Settings\SettingsEntity;
use App\Install\Factory\DatabaseFactory;
use App\Install\Factory\WebsiteFactory;
use App\UI\Backend\Sign\SignUpFactory;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Application\UI\Alert;
use Drago\Localization\TranslatorAdapter;
use Install\InstallLock;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;


/**
 * Installation and configuration application.
 * @property InstallTemplate $template
 */
final class InstallPresenter extends Presenter
{
	use TranslatorAdapter;

	public function __construct(
		private readonly string $tempPath,
		private readonly Connection $connection,
		private readonly Steps $steps,
		private readonly DatabaseFactory $databaseFactory,
		private readonly WebsiteFactory $websiteFactory,
		private readonly SignUpFactory $userSingUpFactory,
		private readonly MigrationService $migrationService,
	) {
		parent::__construct();
	}


	/** Prepare the installation step before rendering. */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$step = $this->steps->getStep();
		$this->template->step = $step ?? 0;
		$this->template->migrationFiles = $this->migrationService->getFiles();
	}


	/** Render default installation page. */
	public function renderDefault(): void
	{
		$this->redrawControl('install');
	}


	/** Handle the installation process start. */
	public function handleRun(): void
	{
		$this->steps->setStep(1);
	}


	/** Handle migration run. */
	public function handleRunMigration(string $file): void
	{
		$this->sendJson($this->migrationService->run($file));
	}


	/** Handle migration success. */
	public function handleMigrationsDone(): void
	{
		$this->steps->setStep(3);
		$this->flashMessage('Database installation was successful.', Alert::Success);
	}


	/** Handle migration failure. */
	public function handleMigrationsFail(): void
	{
		$this->flashMessage('Database installation failed.', Alert::Danger);
	}


	/** Create and return the database configuration form. */
	protected function createComponentDatabase(): Form
	{
		$form = $this->databaseFactory->create();
		$form->setTranslator($this->translator);
		$form->onSuccess[] = function () {
			$this->steps->setStep(2);
			$this->flashMessage('Database settings were successful.', Alert::Success);
		};
		return $form;
	}


	/** Create and return the website configuration form. */
	protected function createComponentWebsite(): Form
	{
		$form = $this->websiteFactory->create();
		$form->setTranslator($this->translator);
		$form->onSuccess[] = function () {
			$this->steps->setStep(4);
			$this->flashMessage('Site settings successful.', Alert::Success);
		};
		return $form;
	}


	/** Create and return the account creation form for the administrator. */
	protected function createComponentAccount(): Form
	{
		$form = $this->userSingUpFactory->create();
		$form->setTranslator($this->translator);
		$form->onSuccess[] = function () {
			$this->connection->insert(UsersRolesEntity::Table, [
				UsersRolesEntity::ColumnUserId => 1,
				UsersRolesEntity::ColumnRoleId => 1,
			])->execute();
			$this->steps->setStep(5);
			$this->flashMessage('Account administrator registration successful.', Alert::Success);
		};

		return $form;
	}


	/** @throws Exception */
	public function handleFinish(): void
	{
		$this->connection->insert(SettingsEntity::Table, [
			SettingsEntity::ColumnName => 'installed',
			SettingsEntity::ColumnValue => '1',
		])->execute();

		InstallLock::create(dirname($this->tempPath));
		$this->redirectUrl('/admin');
	}
}
