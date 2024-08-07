<?php

declare(strict_types=1);

namespace App\UI\Backend;

use App\UI\Template;
use Nette\Security\User;


abstract class BackendTemplate extends Template
{
	public User|\App\Core\User\User $user;
}
