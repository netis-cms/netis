<?php

declare(strict_types=1);

namespace App\Core\Permission\Authorization;

use App\Core\Permission\BaseTemplate;
use Dibi\Row;


/** Authorization template. */
class AuthorizationTemplate extends BaseTemplate
{
	/** @var array<string, Row[]> */
	public array $groupedPermissions = [];

	/** @var array<int, string> */
	public array $roles = [];
	public string $roleName = '';
	public int $roleId = 0;
	public int $allowedCount = 0;
	public int $deniedCount = 0;
	public bool $isAdminRole = false;
}
