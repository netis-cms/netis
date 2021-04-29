<?php

declare(strict_types=1);

namespace Module\Install\Service;

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
