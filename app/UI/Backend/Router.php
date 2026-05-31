<?php

declare(strict_types=1);

namespace App\UI\Backend;

use Nette;
use Nette\Application\Routers\RouteList;


/** Backend router. */
final class Router
{
	use Nette\StaticClass;

	/** Create router. */
	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Backend')
			->addRoute('[<lang=cs cs|en>/]admin/<presenter>/<action>', 'Admin:default');

		return $router;
	}
}
