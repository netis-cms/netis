<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;


trait SettingsRequire
{
	#[Inject]
	public SettingsRepository $settingsRepository;


	public function injectSettings(Presenter $presenter): void
	{
		$presenter->onRender[] = function () use ($presenter) {
			$settings = $this->settingsRepository->getSettings();
			$settingsRecords = new Settings(
				website: $settings['website'],
				description: $settings['description'],
			);
			$presenter->template->settings = $settingsRecords;
		};
	}
}
