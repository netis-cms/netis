<?php

declare(strict_types=1);

use App\Bootstrap;
use App\Core\Settings\SettingsEntity;
use Dibi\Connection;
use Install\InstallLock;
use Nette\DI\Container;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

// Composer autoload
require __DIR__ . '/../vendor/autoload.php';


/** Application runner class to manage Nette application lifecycle. */
class ApplicationRunner
{
	private Bootstrap $bootstrap;


	public function __construct()
	{
		// Initialize the Bootstrap class for app configuration
		$this->bootstrap = new Bootstrap;
	}


	/**
	 * Run the Nette application.
	 * @throws Throwable
	 */
	public function run(): void
	{
		// Create the container and get the application service
		$lockFileDir = dirname(__DIR__);
		if (is_file(InstallLock::getPath($lockFileDir))) {
			$container = $this->bootstrap
				->bootWebApplication();

		} else {
			$container = $this->bootstrap->bootInstallApplication();
			if ($this->recreateLockIfInstalled($container)) {
				InstallLock::create($lockFileDir, 'recreated from db');
				$container->getByType(IResponse::class)->redirect(
					$container->getByType(IRequest::class)
						->getUrl()
						->getAbsoluteUrl(),
				);
				exit;
			}
		}

		$app = $container->getByType(Nette\Application\Application::class);
		$app->run();
	}


	/** Recreate the lock file if the application is already installed. */
	private function recreateLockIfInstalled(Container $container): bool
	{
		try {
			$db = $container->getByType(Connection::class);
			return (bool) $db->select('value')
				->from(SettingsEntity::Table)
				->where('%n = ?', SettingsEntity::ColumnName, 'installed')
				->fetchSingle();

		} catch (Throwable) {
			return false;
		}
	}
}


(new ApplicationRunner)->run();
