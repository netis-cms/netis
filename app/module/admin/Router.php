<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Admin\Module;

use Nette;
use Nette\Application\Routers;

/**
 * Router factory.
 */
class Router
{
	use Nette\StaticClass;

	/**
	 * @param string $locales
	 * @return Nette\Application\IRouter
	 */
	public static function create($locales)
	{
		$router = new Routers\RouteList;
		$router[] = $module = new Routers\RouteList('Admin');
		$module[] = new Routers\Route($locales . 'admin/<presenter>/<action>/[<id>/]', 'Admin:default');
		return $router;
	}

}
