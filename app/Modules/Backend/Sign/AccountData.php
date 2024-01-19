<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

use Drago\Utils\ExtraArrayHash;


class AccountData extends ExtraArrayHash
{
	public const ColumnUsername = 'username';
	public const ColumnEmail = 'email';
	public const ColumnPassword = 'password';
	public const Verify = 'verify';

	public string $username;
	public string $email;
	public string $password;
	public string $verify;
	public string $token;
}
