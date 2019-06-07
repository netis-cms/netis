<?php

declare(strict_types = 1);

namespace App;

use Nette;
use Entity\WebsiteEntity;
use Drago\Localization\Locale;
use Drago\Localization\Translator;
use Repository\WebsiteRepository;
use Tracy\Debugger;


/**
 * Base class for frontend module.
 */
class FrontPresenter extends BasePresenter
{
	use Locale;

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
	protected function beforeRender(): void
	{
		parent::beforeRender();

		// If install directory exists, we will install application.
		if (is_dir(__DIR__ . '/install')) {
			$this->redirect(':Install:Install:');
		}

		// The current language parameter.
		$this->template->lang = $this->lang;

		// Translation for templates.
		$this->template->setTranslator($this->getTranslator());

		// Website settings for templates.
		$this->template->web = (object) $this->websiteRepository->get()->fetchPairs();
	}


	public function getTranslator(): Translator
	{
		$file = __DIR__ . '/web/locale/' . $this->lang . '.ini';
		return $this->createTranslator($file);
	}
}
