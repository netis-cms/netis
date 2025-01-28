<?php

declare(strict_types=1);

namespace App\UI;

use App\Core\Settings\Settings;
use Drago\Application\UI\ExtraTemplate;


/**
 * Custom template class that extends the ExtraTemplate class.
 * It includes an additional language property.
 */
class Template extends ExtraTemplate
{
	public string $lang;
	public Settings $settings;
}
