<?php

declare(strict_types=1);

namespace App\Core\Permission;

use Drago\Database\Entity;


/** Permission entity. */
class PermissionEntity extends Entity
{
	public string $role;
	public string $privilege;
	public string $resource;
}
