<?php

declare(strict_types=1);

namespace App\Install;

use Drago\Application\UI\ExtraTemplate;


/** Template for the installation process. */
class InstallTemplate extends ExtraTemplate
{
	public string $lang;
	public int $step;

	/** @var list<string> */
	public array $migrationFiles = [];
}
