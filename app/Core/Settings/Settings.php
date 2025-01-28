<?php

declare(strict_types=1);

namespace App\Core\Settings;


/**
 * This class holds configuration for the website such as website name and description.
 */
class Settings
{
	/**
	 * Website name
	 */
	public string $website;

	/**
	 * Website description
	 */
	public string $description;


	public function __construct(
		string $website,
		string $description,
	) {
		$this->website = $website;
		$this->description = $description;
	}
}
