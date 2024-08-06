<?php

declare(strict_types=1);

namespace App\UI\Backend;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


final class Router
{
	use StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Backend')
			->addRoute('[<lang=cs cs|en>/]admin/<presenter>/<action>', 'Admin:default');

		return $router;
	}
}
