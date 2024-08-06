<?php

declare(strict_types=1);

namespace App\Core\User;

use Drago\Utils\ExtraArrayHash;


class UserData extends ExtraArrayHash
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
