<?php

declare(strict_types = 1);

namespace Module\Install\Service;

use Nette\Caching\Cache;


/**
 * Saving installation steps into cache.
 */
class Steps
{
	/** @var Cache */
	public $cache;

	/** Install steps. */
	const STEP = 'Install step';


	public function __construct(Cache $cache)
	{
		$this->cache = $cache;
	}
}
