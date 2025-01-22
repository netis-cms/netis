<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Exception;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use RuntimeException;
use Tracy\Debugger;
use Tracy\ILogger;


/**
 * This trait allows presenters to easily inject settings from the repository
 * and assign them to the template for rendering.
 */
trait SettingsRequire
{
	#[Inject]
	public SettingsRepository $settingsRepository;

	/**
	 * This method fetches settings from the repository and assigns them to
	 * the presenter template as a Settings object.
	 */
	public function injectSettings(Presenter $presenter): void
	{
		$presenter->onRender[] = function () use ($presenter) {
			try {
				// Fetch settings from the repository
				$settings = $this->settingsRepository->getSettings();

				// Ensure required settings are present
				if (!isset($settings['website'], $settings['description'])) {
					throw new RuntimeException('Required settings "website" or "description" are missing.');
				}

				// Create a Settings object from fetched data
				$settingsRecords = new Settings(
					website: $settings['website'],
					description: $settings['description'],
				);

				// Assign settings to the template
				$presenter->template->settings = $settingsRecords;

			} catch (Exception $e) {
				// Handle errors, log them, or assign empty/default settings as needed
				// For now, let's just log the error
				Debugger::log($e, ILogger::EXCEPTION);

				// Optionally, assign default empty settings or throw an error as needed
				$presenter->template->settings = new Settings(website: '', description: '');
			}
		};
	}
}
