<?php

declare(strict_types=1);

namespace App\Modules\Front;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


final class Router
{
	use StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Front')
			->addRoute('[<lang=en cs>/]<presenter>/<action>', 'Home:default');

		return $router;
	}
}
