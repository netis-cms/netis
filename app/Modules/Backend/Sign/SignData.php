<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class SignData extends ExtraArrayHash
{
	use SmartObject;

	public const email = 'email';
	public const password = 'password';

	public string $email;
	public string $password;
}
