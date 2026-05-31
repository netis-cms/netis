<?php

declare(strict_types=1);

namespace App\UI\Backend\Admin;

use Drago\Permission\Provider;
use Drago\Permission\Role;
use Nette\Security\Permission;

final class AdminPermission implements Provider
{
	private const string Resource = 'Backend:Admin';


	public function register(Permission $acl): void
	{
		$acl->addResource(self::Resource);
		$acl->allow(Role::RoleAdmin);
	}
}
