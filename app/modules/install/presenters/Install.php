<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Install\Module;

use Install;
use Install\Service;

use Drago;
use Drago\Localization;

use Nette\Application\UI;

/**
 * Installation and configuration application.
 */
final class InstallPresenter extends UI\Presenter
{
	use Drago\Application\UI\Drago;
	use Localization\Locales;

	/**
	 * Templates for redirects.
	 */
	const
		TEMPLATE_STEP    = 'step',
		TEMPLATE_DEFAULT = 'default',
		TEMPLATE_FINAL   = 'completed';

	/**
	 * @var Service\Steps
	 * @inject
	 */
	public $steps;

	/**
	 * @var Install\Forms
	 * @inject
	 */
	public $forms;

	/**
	 * @return Localization\Translator
	 */
	public function getTranslator()
	{
		$path = __DIR__ . '/../locales/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}

	protected function startup()
	{
		parent::startup();
		$this->template->step = 0;
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->setTranslator($this->getTranslator());
		$this->template->lang = $this->lang;
	}

	/**
	 * Processing installation steps.
	 * @param int $id
	 */
	public function actionStep($id = 0)
	{
		// Step parameter for template.
		$this->template->step = $id;

		// Checking installation steps.
		switch ($id) {

			// Check 1 step.
			case 1:
				if (!$this->steps->cache->load(Service\Steps::START)) {
					$this->redirect(self::TEMPLATE_DEFAULT);
				} else {
					if ($this->steps->cache->load(Service\Steps::STEP_1)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 2]);
					}
				}
			break;

			// Check 2 step.
			case 2:
				if (!$this->steps->cache->load(Service\Steps::START)) {
					$this->redirect(self::TEMPLATE_DEFAULT);
				} else {
					if ($this->steps->cache->load(Service\Steps::STEP_2)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 3]);

					} elseif (!$this->steps->cache->load(Service\Steps::STEP_1)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 1]);
					}
				}
			break;

			// Check 3 step.
			case 3:
				if (!$this->steps->cache->load(Service\Steps::START)) {
					$this->redirect(self::TEMPLATE_DEFAULT);
				} else {
					if ($this->steps->cache->load(Service\Steps::STEP_3)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 4]);

					} elseif (!$this->steps->cache->load(Service\Steps::STEP_1)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 1]);

					} elseif (!$this->steps->cache->load(Service\Steps::STEP_2)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 2]);
					}
				}
			break;

			// Check 4 step.
			case 4:
				if (!$this->steps->cache->load(Service\Steps::START)) {
					$this->redirect(self::TEMPLATE_DEFAULT);
				} else {
					if ($this->steps->cache->load(Service\Steps::STEP_4)) {
						$this->redirect(self::TEMPLATE_FINAL);

					} elseif (!$this->steps->cache->load(Service\Steps::STEP_1)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 1]);

					} elseif (!$this->steps->cache->load(Service\Steps::STEP_2)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 2]);

					} elseif (!$this->steps->cache->load(Service\Steps::STEP_3)) {
						$this->redirect(self::TEMPLATE_STEP, ['id' => 3]);
					}
				}
			break;

			// Page not found.
			default:
				$this->error();
			break;
		}
	}

	// Redirect from the template when it has already started installation.
	public function actionDefault()
	{
		if ($this->steps->cache->load(Service\Steps::START)) {
			$this->redirect(self::TEMPLATE_STEP, ['id' => 1]);
		}
	}

	// Redirect to default template if step 4 has not been completed.
	public function actionCompleted()
	{
		if (!$this->steps->cache->load(Service\Steps::STEP_4)) {
			$this->redirect(self::TEMPLATE_DEFAULT);
		}
	}

	// Run the installation.
	public function handleRun()
	{
		$this->steps->cache->save(Service\Steps::START, rand(1, 9));
		$this->redirect(self::TEMPLATE_STEP, ['id' => 1]);
	}

	/**
	 * @return Install\Forms
	 */
	protected function createComponentDatabase()
	{
		return $this->forms->databaseHostFactory($this->getTranslator(), function () {
			$this->flashMessageSuccess('install.db.message');
			$this->redirect(self::TEMPLATE_STEP, ['id' => 2]);
		});
	}

	/**
	 * @return Install\Forms
	 */
	protected function createComponentTables()
	{
		return $this->forms->dbTablesFactory($this->getTranslator(), function () {
			$this->flashMessageSuccess('install.db.tables.message');
			$this->redirect(self::TEMPLATE_STEP, ['id' => 3]);
		});
	}

	/**
	 * @return Install\Forms
	 */
	protected function createComponentWebsite()
	{
		return $this->forms->websiteSettingsFactory($this->getTranslator(), function () {
			$this->flashMessageSuccess('install.web.message');
			$this->redirect(self::TEMPLATE_STEP, ['id' => 4]);
		});
	}

	/**
	 * @return Install\Forms
	 */
	protected function createComponentAccount()
	{
		return $this->forms->registrationAccount($this->getTranslator(), function () {
			$this->flashMessageSuccess('install.acc.message');
			$this->redirect(self::TEMPLATE_FINAL);
		});
	}

}
