<?php

declare(strict_types=1);

namespace App\UI\Front;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


final class Router
{
	use StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;

		// Adding the route to handle languages
		$router->withModule('Front')
			// Define the lang as an optional parameter with default value
			->addRoute('[<lang=cs|en>]/<presenter>/<action>', 'Home:default');

		return $router;
	}
}
