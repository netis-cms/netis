<?php

declare(strict_types=1);

namespace Module\Web;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


final class Router
{
	use StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Web')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Web:default');

		return $router;
	}
}
