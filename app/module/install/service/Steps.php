<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Module\Install\Service;

use Nette\Caching;

/**
 * Saving installation steps into cache.
 */
class Steps
{
	/**
	 * @var Caching\Cache
	 */
	public $cache;

	/**
	 * Install steps.
	 */
	const STEP = 'Install step';


	public function __construct(Caching\Cache $cache)
	{
		$this->cache = $cache;
	}
}
