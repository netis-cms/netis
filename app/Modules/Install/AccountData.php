<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class AccountData extends ExtraArrayHash
{
	use SmartObject;

	public const USERNAME = 'username';
	public const EMAIL = 'email';
	public const PASSWORD = 'password';
	public const VERIFY = 'verify';

	public string $username;
	public string $email;
	public string $password;
	public string $verify;
	public string $token;
}
