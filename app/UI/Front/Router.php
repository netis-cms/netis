<?php

declare(strict_types=1);

namespace App\UI\Front;

use Nette;
use Nette\Application\Routers\RouteList;


/** Router for the Front module. */
final class Router
{
	use Nette\StaticClass;

	/** Creates and configures the route list for the front-end module. */
	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Front')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Home:default');

		return $router;
	}
}
