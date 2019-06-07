<?php

declare(strict_types = 1);

namespace App;

use Nette;
use Drago\Localization\Locale;
use Drago\Localization\Translator;


/**
 * Base class for frontend module.
 */
class FrontPresenter extends BasePresenter
{
	use Locale;

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

		// Translation for Templates.
		$this->template->setTranslator($this->getTranslator());
	}


	public function getTranslator(): Translator
	{
		$file = __DIR__ . '/web/locale/' . $this->lang . '.ini';
		return $this->createTranslator($file);
	}
}
