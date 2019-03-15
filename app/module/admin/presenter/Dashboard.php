<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Module\Admin;

use Base;
use Drago;
use Supplement;
use Repository;

/**
 * The basic for admin module.
 */
abstract class DashboardPresenter extends Base\BasePresenter
{
	/**
	 * @var Supplement\Gravatar
	 * @inject
	 */
	public $gravatar;

	/**
	 * @var Repository\Menu
	 * @inject
	 */
	public $repositoryMenu;


	/**
	 * @return Drago\Localization\Translator
	 */
	public function translator()
	{
		parent::translator();
		$path = __DIR__ . '/../locale/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}


	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Admin:Sign:in');
		}
	}


	protected function beforeRender()
	{
		parent::beforeRender();
		$this->setLayout('dashboard');
		$this->template->gravatar = $this->gravatar();
		$this->template->category = $this->repositoryMenu->category();
		$this->template->menu = $this->repositoryMenu->all();
	}


	/**
	 * @return string
	 */
	public function gravatar($size = null)
	{
		$email = $this->user->identity->data['email'];
		return $this->gravatar->getGravatar($email, $size);
	}
}
