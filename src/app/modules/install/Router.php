<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Install\Module;

use Nette;
use Nette\Application\Routers;

/**
 * Router factory.
 */
class Router
{
	use Nette\StaticClass;

	/**
	 * @param string
	 * @return Nette\Application\IRouter
	 */
	public static function create($locales)
	{
		$router = new Routers\RouteList;
		$router[] = $module = new Routers\RouteList('Install');
		$module[] = new Routers\Route($locales . 'install/<action>/[<id>/]', 'Install:default');
		$module[] = new Routers\Route($locales . 'install/[<presenter>/]<action>/[<id>/]', 'Install:default');
		return $router;
	}

}
