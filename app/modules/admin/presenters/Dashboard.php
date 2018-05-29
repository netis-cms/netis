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
abstract class DashboardPresenter extends Base\BasePresenter
{
	// ini var.
	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Admin:Login:', [
				'backlink' => $this->storeRequest()
			]);
		}
	}

	// Setup rendering.
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->setLayout($this->getModuleDir() . '/admin/presenters/templates/@layout.latte');
	}

}
