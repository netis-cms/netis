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
use Nette\Utils\ArrayHash;

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
	 * @var Application\UI\Factory
	 * @inject
	 */
	public $factory;

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

		// The currently used language.
		$this->template->lang = $this->lang;

		// Website settings.
		$website = ArrayHash::from($this->website->all());
		$this->template->web  = $website;
	}

	/**
	 * Translator.
	 * @param string
	 * @param int
	 * @return Localization\Translator
	 */
	public function translator($module)
	{
		$path = $this->getModuleDir() . '/' . $module . '/locales/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}

}
