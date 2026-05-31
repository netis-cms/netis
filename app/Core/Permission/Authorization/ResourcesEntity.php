<?php

declare(strict_types=1);

namespace App\Core\Permission\Authorization;

use Drago\Database\Entity;


/** Resources entity. */
class ResourcesEntity extends Entity
{
	public const string
		Table = 'resources',
		PrimaryKey = 'id',
		ColumnResource = 'resource',
		ColumnPrivilege = 'privilege',
		ColumnDescription = 'description';

	public int $id;
	public string $resource;
	public string $privilege;
	public string $description;
	public ?string $access = null;
}
