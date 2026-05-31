<?php

declare(strict_types=1);

namespace App\Core\User;


/** User identity. */
class UserIdentity
{
	public function __construct(
		public string $username,
		public string $email,
	) {
	}
}
