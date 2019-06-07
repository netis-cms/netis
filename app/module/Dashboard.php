<?php

declare(strict_types = 1);

namespace App;

use Drago\Localization\Translator;


/**
 * Dashboard class for admin module.
 */
class DashboardPresenter extends BasePresenter
{
	protected function startup(): void
	{
		parent::startup();
		$this->setTemplate('layout', 'dev');
	}


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
