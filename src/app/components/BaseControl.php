<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Component;

use Drago\Localization;
use Nette\Application\UI;

/**
 * Base for components.
 */
abstract class BaseControl extends UI\Control
{
	/**
	 * @var Localization\Translator
	 */
	public $translator;

	/**
	 * @param Localization\Translator
	 */
	public function setTranslator($translator)
	{
		$this->translator = $translator;
	}

}
