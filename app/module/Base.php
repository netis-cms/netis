<?php

namespace Base;

use Nette\Application\UI;
use Repository\SettingsRepository;
use Tracy\Debugger;


abstract class Base extends UI\Presenter
{
	/**
	 * @var SettingsRepository
	 * @inject
	 */
	public $settingsRepository;

	protected function startup(): void
	{
		parent::startup();
		if (is_dir(__DIR__ . '/install')) {
			$this->redirect(':Install:Install:');
		}

		$mode = Debugger::$productionMode;
		$mode ? $this->setLayout('layout') : $this->setLayout('dev');

		$settings = (object) $this->settingsRepository->all()->fetchPairs();
		$this->template->settings = $settings;
	}
}
