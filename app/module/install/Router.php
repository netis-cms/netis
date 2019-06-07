<?php

namespace Module\Install;

use Nette;
use Nette\Application\Routers;


class Router
{
	use Nette\StaticClass;


	public static function create(): Nette\Routing\Router
	{
		$router = new Routers\RouteList;
		$router
			->withModule('Install')
			->addRoute('[<lang=cs cs|en>/]install/', 'Install:default');

		return $router;
	}
}
