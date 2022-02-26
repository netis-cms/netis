<?php

declare(strict_types=1);

namespace App\Modules;

use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use App\Services\Repository\SettingsRepository;


abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
	use Authorization;

	#[Inject]
	public SettingsRepository $settingsRepository;


	protected function startup(): void
	{
		parent::startup();
		$this->template->settings = (object) $this->settingsRepository->all()->fetchPairs();
	}


	public function injectInstall(Presenter $presenter)
	{
		$presenter->onStartup[] = function () use ($presenter) {
			if (is_dir(__DIR__ . '/Install')) {
				$presenter->redirect(':Install:Install:');
			}
		};
	}
}
