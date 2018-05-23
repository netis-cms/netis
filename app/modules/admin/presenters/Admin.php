<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Admin\Module;

/**
 * Administration of the site.
 */
final class AdminPresenter extends DashboardPresenter
{
	// Setup rendering.
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->setTranslator($this->translator('admin'));
	}

}
