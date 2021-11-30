<?php

declare(strict_types=1);

namespace App\Modules;

use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use App\Services\Repository\SettingsRepository;
use Tracy\Debugger;


abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
	use Authorization;

	#[Inject]
	public SettingsRepository $settingsRepository;


	public function injectInstall(Presenter $presenter)
	{
		$presenter->onStartup[] = function () use ($presenter) {
			if (is_dir(__DIR__ . '/Install')) {
				$presenter->redirect(':Install:Install:');
			}
		};
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->mode = Debugger::$productionMode;
		$this->template->settings = (object) $this->settingsRepository->all()->fetchPairs();
	}
}
