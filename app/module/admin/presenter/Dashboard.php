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
	public $menu;

	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect(':Admin:Login:');
		}
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->gravatar = $this->gravatar();
		$this->template->category = $this->menu->category();
		$this->template->menu = $this->menu->all();
	}

	/**
	 * @return string
	 */
	public function gravatar()
	{
		$email = $this->user->identity->data['email'];
		return $this->gravatar->getGravatar($email);
	}

	/**
	 * @return Drago\Localization\Translator
	 */
	public function translator()
	{
		parent::translator();
		$path = __DIR__ . '/../locale/' . $this->lang . '.ini';
		return $this->createTranslator($path);
	}

}
