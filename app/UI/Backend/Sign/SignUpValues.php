<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Drago\Utils\ExtraArrayHash;


/** Sign up values. */
class SignUpValues extends ExtraArrayHash
{
	public const string
		Username = 'username',
		Email = 'email',
		Password = 'password',
		Verify = 'verify';

	public string $username;
	public string $email;
	public string $password;
	public string $verify;
	public string $token;
}
