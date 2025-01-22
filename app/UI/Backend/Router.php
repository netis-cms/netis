<?php

declare(strict_types=1);

namespace App\UI\Backend;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


final class Router
{
	use StaticClass;

	/**
	 * Creates and configures the routing for the backend module.
	 */
	public static function create(): RouteList
	{
		$router = new RouteList;

		// Define the routes for the Backend module
		$router->withModule('Backend')
			->addRoute('[<lang=cs|en>/]admin/<presenter>/<action>', 'Admin:default');

		return $router;
	}
}
