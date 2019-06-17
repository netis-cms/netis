<?php

declare(strict_types = 1);

namespace App;

use Nette;
use Nette\Application\UI\Presenter;
use Drago\Application\UI\Factory;
use Drago\Parameters\Environment;
use Drago\Localization\Locale;
use Repository\WebsiteRepository;
use Entity\WebsiteEntity;


/**
 * Base class for all modules.
 */
abstract class BasePresenter extends Presenter
{
	use Locale;
	use Factory;

	/**
	 * @var Environment
	 * @inject
	 */
	public $environment;

	/** @var WebsiteRepository
	 *  @inject
	 */
	public $websiteRepository;

	/**
	 * @var WebsiteEntity
	 * @inject
	 */
	public $websiteEntity;


	/**
	 * @throws Nette\Application\AbortException
	 */
	protected function startup(): void
	{
		parent::startup();

		// If install directory exists, we will install application.
		if (is_dir(__DIR__ . '/install')) {
			$this->redirect(':Install:Install:');
		}
	}


	/**
	 * Set layout for production and dev mode.
	 */
	public function setTemplate(string $productionLayout, string $devLayout): void
	{
		$mode = $this->environment->isProduction();
		$mode ? $this->setLayout($productionLayout) : $this->setLayout($devLayout);
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		// The current language parameter.
		$this->template->lang = $this->lang;

		// Website settings for templates.
		$webSettings = $this->websiteRepository->all()->fetchPairs();
		$this->template->web = (object) $webSettings;
	}
}
