<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Drago\Permission\Provider;
use Drago\Permission\Role;
use Nette\Security\Permission;

final class SignPermission implements Provider
{
	private const string Resource = 'Backend:Sign';


	public function register(Permission $acl): void
	{
		$acl->addResource(self::Resource);
		$acl->allow(Role::RoleGuest, self::Resource);
	}
}
