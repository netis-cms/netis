<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use Drago\Permission\Provider;
use Drago\Permission\Role;
use Nette\Security\Permission;

final class HomePermission implements Provider
{
	private const string Resource = 'Front:Home';


	public function register(Permission $acl): void
	{
		$acl->addResource(self::Resource);
		$acl->allow(Role::RoleGuest, self::Resource);
	}
}
