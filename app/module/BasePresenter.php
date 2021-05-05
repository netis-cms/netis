<?php

declare(strict_types=1);

namespace Base;

use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use Repository\SettingsRepository;
use Tracy\Debugger;


abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;

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

		$presenter = $this->getPresenter();
		$user = $this->getUser();
		$signal = $presenter->getSignal();
		if ((!empty($signal[0])) && isset($signal[1])) {
			if (!$user->isAllowed($presenter->getName(), $signal[0])) {
				$this->error('Forbidden', 403);
			}
		} else {
			if (!$user->isAllowed($presenter->getName(), $signal[1] ?? $presenter->getAction())) {
				$this->error('Forbidden', 403);
			}
		}

		$this->template->mode = Debugger::$productionMode;
		$this->template->settings = (object) $this->settingsRepository->all()->fetchPairs();
	}
}
