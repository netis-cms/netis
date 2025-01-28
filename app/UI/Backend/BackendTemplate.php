<?php

declare(strict_types=1);

namespace App\UI\Backend;

use App\Core\User\User as AppUser;
use App\UI\Template;
use Nette\Security\User as NetteUser;


/**
 * Abstract class for backend templates.
 * This class provides access to the logged-in user, either from Nette Security or custom User class.
 */
abstract class BackendTemplate extends Template
{
	/**
	 * The logged-in user.
	 * This can be either the Nette User or a custom User class from App\Core\User.
	 */
	public NetteUser|AppUser $user;
}
