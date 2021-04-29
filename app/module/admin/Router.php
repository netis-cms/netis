<?php

declare(strict_types=1);

namespace Module\Admin;

use Nette;
use Nette\Application\Routers;


final class Router
{
	use Nette\StaticClass;

	public static function create(): Nette\Routing\Router
	{
		$router = new Routers\RouteList;
		$router
			->withModule('Admin')
			->addRoute('[<lang=cs cs|en>/]admin/<presenter>/<action>', 'Admin:default');

		return $router;
	}
}
