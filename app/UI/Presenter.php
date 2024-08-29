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


	public function injectInstall(self $presenter): void
	{
		if (is_dir(__DIR__ . '/Install')) {
			$presenter->onStartup[] = function () use ($presenter) {
				$presenter->redirect(':Install:Install:');
			};
		}
	}
}
