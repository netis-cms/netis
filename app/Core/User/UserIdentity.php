<?php

declare(strict_types=1);

namespace App\Core\User;


/**
 * Class representing the user's identity.
 * Stores information about the username and email address.
 */
class UserIdentity
{
	public function __construct(
		public string $username,
		public string $email,
	) {
	}
}
