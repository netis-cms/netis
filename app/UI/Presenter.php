<?php

declare(strict_types=1);

namespace App\UI;

use Drago\Localization\TranslatorAdapter;


/**
 * @property Template $template
 */
abstract class Presenter extends \Nette\Application\UI\Presenter
{
	use TranslatorAdapter;
}
