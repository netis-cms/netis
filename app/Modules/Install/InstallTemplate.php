<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Application\UI\ExtraTemplate;


class InstallTemplate extends ExtraTemplate
{
	public string $lang;
	public int $step;
}
