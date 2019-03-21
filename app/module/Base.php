<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Base;

use Drago;
use Repository;
use Nette\Application\UI;
use Nette\Utils;

/**
 * The basic for module.
 */
abstract class BasePresenter extends UI\Presenter
{
	use Drago\Application\UI\Factory;
	use Drago\Localization\Locale;

	/**
	 * @var Repository\Website
	 * @inject
	 */
	public $repositoryWebsite;

	/**
	 * @var string
	 */
	public $moduleName;

	/**
	 * @var string
	 */
	public $presenterName;


	/**
	 * @return Drago\Localization\Translator
	 */
	public function translator()
	{
		$path = __DIR__ . '/web/locale/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}


	protected function startup()
	{
		parent::startup();
		if (is_dir(__DIR__ . '/install')) {
			$this->redirect(':Install:Install:');
		}
		$a = strrpos($this->getName(), ':');
		$this->moduleName = $a ? substr($this->getName(), 0, $a + 1) : '';
		$this->presenterName = $a ? substr($this->getName(), $a + 1) : $this->getName();
	}


	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->lang = $this->lang;
		$this->template->setTranslator($this->translator());
		$this->template->web = (object)$this->repositoryWebsite->all();
		$this->template->moduleName = $this->moduleName;
		$this->template->presenterName = $this->presenterName;
		$breadcrumb = Utils\Strings::webalize($this->presenterName);
		$this->template->breadcrumb = 'menu.' . $breadcrumb;
		$this->template->date = date('Y');
	}
}
