<?php

declare(strict_types = 1);

namespace Module\Install;

use Nette;
use Nette\Application\Routers\RouteList;


class Router
{
	use Nette\StaticClass;


	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Install')
			->addRoute('[<lang=cs cs|en>/]install/', 'Install:default');

		return $router;
	}
}
