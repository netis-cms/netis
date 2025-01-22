<?php

declare(strict_types=1);

namespace App\UI\Install;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


final class Router
{
	use StaticClass;


	/**
	 * Create and return the application's routing configuration.
	 *
	 * @return RouteList The list of routes.
	 */
	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Install')
			->addRoute('[<lang=cs cs|en>/]install/', 'Install:default');

		return $router;
	}
}
