<?php

declare(strict_types=1);

namespace App\Modules;

use Drago\Application\UI\ExtraTemplate;


abstract class BaseTemplate extends ExtraTemplate
{
	public string $lang;
	public string $module;
}
