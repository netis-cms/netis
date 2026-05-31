<?php

declare(strict_types=1);

namespace App\Core\Settings;


/** Website configuration (name, description) etc. */
class Settings
{
	public function __construct(
		public string $website,
		public string $description,
	) {
	}
}
