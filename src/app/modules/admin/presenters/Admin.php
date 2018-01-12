<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Admin\Module;
use Base;

/**
 * Administration of the site.
 */
final class AdminPresenter extends Base\DashboardPresenter
{
	// Setup rendering.
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->setTranslator($this->translator('admin'));
	}

}
