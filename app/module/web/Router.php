<?php

declare(strict_types = 1);

namespace Module\Web;

use Nette;
use Nette\Application\Routers\RouteList;


final class Router
{
	use Nette\StaticClass;


	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Web')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Web:default');

		return $router;
	}
}
