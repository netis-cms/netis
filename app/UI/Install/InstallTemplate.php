<?php

declare(strict_types=1);

namespace App\UI\Install;

use App\UI\Template;


/**
 * Template for the installation process.
 * Stores the current installation step.
 */
class InstallTemplate extends Template
{
	/** @var int The current installation step. */
	public int $step;
}
