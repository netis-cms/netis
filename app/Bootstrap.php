<?php

declare(strict_types=1);

namespace App;

use Drago\Bootstrap\ExtraConfigurator;


/**
 * Configure the application.
 */
class Bootstrap
{
	public static function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator;
		$appDir = dirname(__DIR__);

		// Enable debug mode.
		if (getenv('NETTE_DEBUG') === '1') {
			$app->setDebugMode(true);
		}

		// Enable Tracy tool.
		$app->enableTracy($appDir . '/log');

		// Set the time zone.
		$app->setTimeZone('Europe/Prague');

		// Directory of temporary files.
		$app->setTempDirectory($appDir . '/temp');

		// Auto-loading classes.
		$app->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		// Create DI container from configuration files.
		$app->addFindConfig(__DIR__, 'Locales');

		return $app;
	}
}
