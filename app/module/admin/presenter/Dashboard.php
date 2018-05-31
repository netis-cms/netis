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
	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Admin:Login:', [
				'backlink' => $this->storeRequest()
			]);
		}
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->setLayout($this->getModuleDir() . '/admin/presenter/templates/@layout.latte');
	}

}
