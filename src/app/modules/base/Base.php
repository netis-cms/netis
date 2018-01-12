<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Base;

use Drago;
use Drago\Localization;
use Drago\Directory;

use Base\Repository;
use Nette\Application\UI;

/**
 * The basic for modules.
 */
abstract class BasePresenter extends UI\Presenter
{
	use Drago\Application\UI\Drago;
	use Localization\Locales;

	/**
	 * @var Repository\Website
	 * @inject
	 */
	public $website;

	/**
	 * @var Directory\Dirs
	 * @inject
	 */
	public $dirs;

	/**
	 * Modules directory.
	 * @return string
	 */
	public function getModuleDir()
	{
		return $this->dirs->getAppDir() . '/modules';
	}

	// ini var.
	protected function startup()
	{
		parent::startup();

		// Verify the existence of the installation module.
		if (is_dir($this->getModuleDir() . '/install')) {
			$this->redirect(':Install:Install:');
		}
	}

	// Setup rendering.
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->lang = $this->lang;
		$this->template->web  = (object) $this->website->all();
	}

	/**
	 * Translator.
	 * @param string
	 * @param int
	 * @return Localization\Translator
	 */
	public function translator($module, $components = NULL)
	{
		$dir  = $components === 1 ? $this->dirs->getAppDir() . '/components/' : $this->getModuleDir();
		$path = $dir . '/' . $module . '/locales/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}

}
