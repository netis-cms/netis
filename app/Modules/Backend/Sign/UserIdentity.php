<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;


class UserIdentity
{
	public function __construct(
		public string $username,
		public string $email,
	) {
	}
}
