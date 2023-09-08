<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class AccountData extends ExtraArrayHash
{
	use SmartObject;

	public const username = 'username';
	public const email = 'email';
	public const password = 'password';
	public const verify = 'verify';

	public string $username;
	public string $email;
	public string $password;
	public string $verify;
	public string $token;
}
