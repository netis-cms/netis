<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presenters;

use App\Modules\DashboardPresenter;
use Drago\Authorization\Control\Access\AccessControl;
use Drago\Authorization\Control\Permissions\PermissionsControl;
use Drago\Authorization\Control\Privileges\PrivilegesControl;
use Drago\Authorization\Control\Resources\ResourcesControl;
use Drago\Authorization\Control\Roles\RolesControl;
use Drago\Authorization\FileNotFoundException;


final class AccessPresenter extends DashboardPresenter
{
	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		$control = $this->permissionsControl;
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentRolesControl(): RolesControl
	{
		$control = $this->rolesControl;
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentResourcesControl(): ResourcesControl
	{
		$control = $this->resourcesControl;
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		$control = $this->privilegesControl;
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentAccessControl(): AccessControl
	{
		$control = $this->accessControl;
		return $control;
	}
}
