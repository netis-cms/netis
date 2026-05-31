<?php

declare(strict_types=1);

namespace App\Install;

use Nette\Caching\Cache;
use Throwable;


/** Saving installation steps into cache. */
class Steps
{
	private const string CacheKey = 'Install step';


	public function __construct(
		private readonly Cache $cache,
	) {
	}


	/** Save the current installation step to cache. */
	public function setStep(int $step): void
	{
		try {
			$this->cache->save(self::CacheKey, $step);
		} catch (Throwable $e) {
			throw new \RuntimeException('Error saving installation step to cache.', 0, $e);
		}
	}


	/** Get the current installation step from cache. */
	public function getStep(): ?int
	{
		try {
			return $this->cache->load(self::CacheKey);
		} catch (Throwable $e) {
			throw new \RuntimeException('Error loading installation step from cache.', 0, $e);
		}
	}
}
