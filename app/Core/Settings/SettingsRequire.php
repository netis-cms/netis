<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use RuntimeException;
use Tracy\Debugger;
use Tracy\ILogger;


/** Injects settings into presenter template. */
trait SettingsRequire
{
	#[Inject]
	public SettingsRepository $settingsRepository;


	/** Inject settings into template. */
	public function injectSettings(Presenter $presenter): void
	{
		$presenter->onRender[] = function () use ($presenter) {
			try {
				$settings = $this->settingsRepository->getSettings();
				if (!isset($settings['website'], $settings['description'])) {
					throw new RuntimeException('Required settings "website" or "description" are missing.');
				}

				$settingsRecords = new Settings(
					website: $settings['website'],
					description: $settings['description'],
				);
				$presenter->template->settings = $settingsRecords;

			} catch (\Throwable $e) {
				Debugger::log($e, ILogger::EXCEPTION);
				$presenter->template->settings = new Settings(website: '', description: '');
			}
		};
	}
}
