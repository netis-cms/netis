<?php

declare(strict_types = 1);

namespace App;

use Drago\ExtraConfigurator;


/**
 * Configure the application.
 */
class Bootstrap
{
	public static function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator;

		// Enable debug mode.
		//$app->setDebugMode('127.0.0.1');

		// Enable Tracy tool.
		$app->enableTracy(__DIR__ . '/../log');

		// Set the time zone.
		$app->setTimeZone('Europe/Prague');

		// Directory of temporary files.
		$app->setTempDirectory(__DIR__ . '/../storage');

		// Auto-loading classes.
		$app->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		// Create DI container from configuration files.
		$app->addFindConfig(__DIR__);

		return $app;
	}
}
