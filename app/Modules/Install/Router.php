<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Nette\Application\Routers\RouteList;
use Nette\StaticClass;


class Router
{
	use StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Install')
			->addRoute('[<lang=en cs|en>/]install/', 'Install:default');

		return $router;
	}
}
