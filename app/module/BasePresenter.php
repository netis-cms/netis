<?php

declare(strict_types=1);

namespace Base;

use Drago\Authorization\Permission;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use Repository\SettingsRepository;
use Tracy\Debugger;


abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
	use Permission;

	#[Inject]
	public SettingsRepository $settingsRepository;


	/**
	 * @throws AbortException
	 */
	protected function startup(): void
	{
		parent::startup();
		if (is_dir(__DIR__ . '/install')) {
			$this->redirect(':Install:Install:');
		}

		$this->template->mode = Debugger::$productionMode;
		$this->template->settings = (object) $this->settingsRepository->all()->fetchPairs();
	}
}
