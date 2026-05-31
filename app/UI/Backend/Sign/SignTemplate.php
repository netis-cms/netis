<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\UI\Backend\Sign\Recovery\Token;
use App\UI\BaseTemplate;


/** Sign in template. */
final class SignTemplate extends BaseTemplate
{
	public Token $signRecoveryToken;
}
