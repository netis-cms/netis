<?php

declare(strict_types=1);

namespace App\Modules\Install\Services;

use Nette\Caching\Cache;


/**
 * Saving installation steps into cache.
 */
class Steps
{
	public const STEP = 'Install step';


	public function __construct(
		public Cache $cache,
	) {
	}
}
