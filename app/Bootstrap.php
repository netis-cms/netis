<?php

declare(strict_types=1);

namespace App;

use Drago\Bootstrap\ExtraConfigurator;
use Throwable;


/**
 * Configure the application.
 */
class Bootstrap
{
	/**
	 * @throws Throwable
	 */
	public static function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator;
		$appDir = dirname(__DIR__);

		// Enable debug mode.
		//$app->setDebugMode('127.0.0.1');

		// Enable Tracy tool.
		$app->enableTracy($appDir . '/log');

		// Set the time zone.
		$app->setTimeZone('Europe/Prague');

		// Directory of temporary files.
		$app->setTempDirectory($appDir . '/storage');

		// Auto-loading classes.
		$app->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		// Create DI container from configuration files.
		$app->addFindConfig(__DIR__);

		return $app;
	}
}
