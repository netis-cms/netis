<?php

declare(strict_types=1);

namespace App\UI;

use App\Core\Settings\SettingsRequire;
use Drago\Localization\TranslatorAdapter;
use Drago\Permission\Authorization;
use Nette\Application\UI\Presenter;


/**
 * Base presenter for the application.
 * @property-read BaseTemplate $template
 */
abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
	use Authorization;
	use SettingsRequire;
}
