<?php

declare(strict_types = 1);

namespace App;

use Nette;
use Nette\Application\UI\Presenter;
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

		// Environment in application.
		$mode = $this->environment->isProduction();
		$mode ? $this->setLayout('layout') : $this->setLayout('dev');
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();

		// The current language parameter.
		$this->template->lang = $this->lang;

		// Website settings for templates.
		$this->template->web = (object) $this->websiteRepository->get()->fetchPairs();
	}
}
