<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign\Recovery;


/** Represents the recovery token and its associated validation flag. */
class Token
{
	public function __construct(
		public bool $hasToken = false,
		public bool $isTokenChecked = false,
	) {
	}
}
