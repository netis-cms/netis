<?php

declare(strict_types=1);

namespace App\UI;

use App\Core\Settings\SettingsRequire;
use Drago\Authorization\Authorization;
use Drago\Localization\TranslatorAdapter;


/**
 * @property Template $template
 */
abstract class Presenter extends \Nette\Application\UI\Presenter
{
	use TranslatorAdapter;
	use Authorization;
	use SettingsRequire;

	public string $loginLink = ':Backend:Sign:in';


	/**
	 * Check if installation is required and redirect if necessary.
	 */
	public function injectInstall(): void
	{
		// Check if the Install directory exists, meaning installation has not been completed.
		if (is_dir(__DIR__ . '/Install')) {
			// Setup redirect to the install page on application startup.
			$this->onStartup[] = function () {
				$this->redirect(':Install:Install:');
			};
		}
	}
}
