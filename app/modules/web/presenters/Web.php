<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Web\Module;
use Base;

/**
 * Frontend module.
 */
final class WebPresenter extends Base\BasePresenter
{
	// Setup rendering.
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->setTranslator($this->translator('web'));
	}

}
