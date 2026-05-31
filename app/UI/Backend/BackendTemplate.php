<?php

declare(strict_types=1);

namespace App\UI\Backend;

use App\Core\Menu\SidebarItem;
use App\Core\User\UserAccess;
use App\UI\BaseTemplate;


class BackendTemplate extends BaseTemplate
{
	public UserAccess $userAccess;

	/** @var array<string, SidebarItem[]> */
	public array $sidebarMenu = [];
}
