<?php

declare(strict_types=1);

namespace App\UI\Install\Factory;

use Drago\Utils\ExtraArrayHash;


/**
 * Class to hold website configuration data.
 * This class stores website details such as the website URL and description.
 */
class WebsiteData extends ExtraArrayHash
{
	// Constants for keys used in the website data array
	public const string Website = 'website';
	public const string Description = 'description';

	/** @var string The website URL. */
	public string $website;

	/** @var string The website description. */
	public string $description;
}
