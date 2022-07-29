<?php

declare(strict_types=1);

namespace App\Modules;

use App\Services\Repository\SettingsRepository;
use Drago\Attr\AttributeDetectionException;
use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;


/**
 * @property-read BaseTemplate $template
 */
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


	/**
	 * @throws AttributeDetectionException
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->module = $this->getName() . ':' . $this->getView();
		$this->template->settings = $this->settingsRepository->getSettings();
	}
}
