<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Nette\Caching\Cache;
use Throwable;


/**
 * Saving installation steps into cache.
 */
class Steps
{
	private const CacheKey = 'Install step';


	public function __construct(
		private readonly Cache $cache,
	) {
	}


	/**
	 * Save the installation step.
	 */
	public function setStep(int $step): void
	{
		$this->cache->save(self::CacheKey, $step);
	}


	/**
	 * We will get the current installation step.
	 * @throws Throwable
	 */
	public function getStep(): mixed
	{
		return $this->cache
			->load(self::CacheKey);
	}
}
