<?php

declare(strict_types=1);

namespace App\Modules\Admin;

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
			->addRoute('[<lang=en cs>/]admin/<presenter>/<action>', 'Admin:default');

		return $router;
	}
}
