<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Module\Admin;

use Nette;
use Nette\Application\Routers;

/**
 * Router factory.
 */
class Router
{
	use Nette\StaticClass;

	/**
	 * @param string $locale
	 * @return Nette\Application\IRouter
	 */
	public static function create($locale)
	{
		$router = new Routers\RouteList;
		$router[] = $module = new Routers\RouteList('Admin');
		$module[] = new Routers\Route($locale . 'admin/<presenter>/<action>/[<id>/]', 'Admin:default');
		return $router;
	}

}
