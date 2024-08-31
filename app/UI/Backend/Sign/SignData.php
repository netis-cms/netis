<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Drago\Utils\ExtraArrayHash;


class SignData extends ExtraArrayHash
{
	public const Email = 'email';
	public const Password = 'password';

	public string $email;
	public string $password;
}
