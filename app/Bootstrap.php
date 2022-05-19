<?php

declare(strict_types=1);

namespace App;

use Drago\Bootstrap\ExtraConfigurator;
use Throwable;


/**
 * Configure the application.
 */
final class Bootstrap
{
	/**
	 * @throws Throwable
	 */
	public static function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator;
		$appDir = dirname(__DIR__);

		// Enable debug mode.
		if (getenv('NETTE_DEBUG') === '1') {
			$app->setDebugMode(true);
		}

		$app->enableTracy($appDir . '/var/log');
		$app->setTimeZone('Europe/Prague');
		$app->setTempDirectory($appDir . '/var');
		$app->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		// Create DI container from configuration files.
		$app->addFindConfig(__DIR__, exclude: 'locales');

		return $app;
	}
}
