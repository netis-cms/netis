<?php

declare(strict_types=1);

namespace App\Modules;

use App\Services\Settings;
use App\Services\SettingsRepository;
use Drago\Attr\AttributeDetectionException;
use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;


/**
 * @property BaseTemplate $template
 */
abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
	use Authorization;

	#[Inject]
	public SettingsRepository $settingsRepository;

	protected string $loginLink = ':Backend:Sign:';


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
		$this->template->module = $this->getName() . ':' . $this->getView();
		$settings = $this->settingsRepository->getSettings();
		$settingsData = new Settings(
			website: $settings['website'],
			description: $settings['description'],
		);

		$this->template->settings = $settingsData;
	}
}
