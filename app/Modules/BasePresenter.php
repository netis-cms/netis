<?php

declare(strict_types=1);

namespace App\Modules;

use App\Services\Repository\SettingsRepository;
use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;


/**
 * @property-read BaseTemplate $template
 */
abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
	use Authorization;

	public function __construct(
		private SettingsRepository $settingsRepository
	) {
		parent::__construct();
	}


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


	protected function beforeRender(): void
	{
		$this->template->module = $this->getName() . ':' . $this->getView();
	}
}
