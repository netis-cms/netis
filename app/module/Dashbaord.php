<?php

declare(strict_types = 1);

namespace App;

use Drago\Localization\Translator;


/**
 * Base class for admin module.
 */
class DashbaordPresenter extends BasePresenter
{
	protected function beforeRender(): void
	{
		parent::beforeRender();

		// Translation for templates.
		$this->template->setTranslator($this->getTranslator());
	}


	public function getTranslator(): Translator
	{
		$file = __DIR__ . '/admin/locale/' . $this->lang . '.ini';
		return $this->createTranslator($file);
	}
}
