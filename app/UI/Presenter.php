<?php

declare(strict_types=1);

namespace App\UI;

use App\Core\Settings\Settings;
use App\Core\Settings\SettingsRepository;
use Drago\Attr\AttributeDetectionException;
use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;
use Nette\DI\Attributes\Inject;


/**
 * @property Template $template
 */
abstract class Presenter extends \Nette\Application\UI\Presenter
{
	use TranslatorAdapter;
	use Authorization;

	public string $loginLink = 'Sign:in';

	#[Inject]
	public SettingsRepository $settingsRepository;


	public function injectInstall(Presenter $presenter): void
	{
		$presenter->onStartup[] = function () use ($presenter) {
			if (is_dir(__DIR__ . '/Install')) {
				$presenter->redirect(':Install:Install:');
			}
		};
	}


	/**
	 * @throws AttributeDetectionException
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$settings = $this->settingsRepository->getSettings();
		$settingsData = new Settings(
			website: $settings['website'],
			description: $settings['description'],
		);

		$this->template->settings = $settingsData;
	}
}
