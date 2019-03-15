<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Module\Web;

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
		$router[] = $module = new Routers\RouteList('Web');
		$module[] = new Routers\Route($locale . '<presenter>/<action>/[<id>/]', 'Web:default');
		return $router;
	}
}
