<?php

declare(strict_types=1);

namespace App\UI\Front;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


/**
 * Router for the Front module.
 * This class defines the routing for the front-end part of the application.
 */
final class Router
{
	use StaticClass;

	/**
	 * Creates and configures the route list for the front-end module.
	 *
	 * @return RouteList The configured route list.
	 */
	public static function create(): RouteList
	{
		$router = new RouteList;
		// Define the route pattern with optional language parameter.
		// The default route points to the Home presenter and its default action.
		$router->withModule('Front')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Home:default');

		return $router;
	}
}
