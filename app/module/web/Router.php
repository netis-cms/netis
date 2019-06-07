<?php

namespace Module\Web;

use Nette;
use Nette\Application\Routers;


final class Router
{
	use Nette\StaticClass;


	public static function create(): Nette\Routing\Router
	{
		$router = new Routers\RouteList;
		$router
			->withModule('Web')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Web:default');

		return $router;
	}
}
