<?php

declare(strict_types=1);

namespace App\UI\Backend\AccessControl;

use Drago\Permission\Provider;
use Drago\Permission\Role;
use Nette\Security\Permission;


/** Access control permission provider. */
class AccessControlPermission implements Provider
{
	private const string Resource = 'Backend:AccessControl';


	/** Registers permissions. */
	public function register(Permission $acl): void
	{
		$acl->addResource(self::Resource);
		$acl->allow(Role::RoleAdmin);
	}
}
