<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Drago\Utils\ExtraArrayHash;


class SignData extends ExtraArrayHash
{
	public const string Email = 'email';
	public const string Password = 'password';

	public string $email;
	public string $password;
}
