<?php

declare(strict_types=1);

namespace App\Modules\Backend\Admin;

use App\Modules\DashboardPresenter;
use Drago\Authorization\Control\Access\AccessControl;
use Drago\Authorization\Control\Permissions\PermissionsControl;
use Drago\Authorization\Control\Privileges\PrivilegesControl;
use Drago\Authorization\Control\Resources\ResourcesControl;
use Drago\Authorization\Control\Roles\RolesControl;


final class AccessPresenter extends DashboardPresenter
{
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		$control = $this->permissionsControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Templates/Permissions/Permissions.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Templates/Permissions/PermissionsItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentRolesControl(): RolesControl
	{
		$control = $this->rolesControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Templates/Roles/Roles.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Templates/Roles/RolesItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentResourcesControl(): ResourcesControl
	{
		$control = $this->resourcesControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Templates/Resources/Resources.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Templates/Resources/ResourcesItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		$control = $this->privilegesControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Templates/Privileges/Privileges.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Templates/Privileges/PrivilegesItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentAccessControl(): AccessControl
	{
		$control = $this->accessControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Templates/Access/Access.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Templates/Access/AccessItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}
}
