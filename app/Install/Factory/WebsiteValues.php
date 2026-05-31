<?php

declare(strict_types=1);

namespace App\Install\Factory;

use Drago\Utils\ExtraArrayHash;


/** Class to hold website configuration data. */
class WebsiteValues extends ExtraArrayHash
{
	public const string
		Website = 'website',
		Description = 'description';

	public string $website;
	public string $description;
}
