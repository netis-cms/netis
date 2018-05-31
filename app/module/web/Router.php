<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Web\Module;

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
		$router[] = $module = new Routers\RouteList('Web');
		$module[] = new Routers\Route($locales . '<presenter>/<action>/[<id>/]', 'Web:default');
		return $router;
	}

}
