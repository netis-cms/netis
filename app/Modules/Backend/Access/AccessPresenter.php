<?php

declare(strict_types=1);

namespace App\Modules\Backend\Access;

use App\Modules\Backend\BackendPresenter;
use Drago\Authorization\Control\Access\AccessControl;
use Drago\Authorization\Control\Permissions\PermissionsControl;
use Drago\Authorization\Control\Privileges\PrivilegesControl;
use Drago\Authorization\Control\Resources\ResourcesControl;
use Drago\Authorization\Control\Roles\RolesControl;


/**
 * @property-read AccessTemplate $template
 */
final class AccessPresenter extends BackendPresenter
{
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		$control = $this->permissionsControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Customize/Permissions/Permissions.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Customize/Permissions/PermissionsItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentRolesControl(): RolesControl
	{
		$control = $this->rolesControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Customize/Roles/Roles.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Customize/Roles/RolesItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentResourcesControl(): ResourcesControl
	{
		$control = $this->resourcesControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Customize/Resources/Resources.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Customize/Resources/ResourcesItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		$control = $this->privilegesControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Customize/Privileges/Privileges.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Customize/Privileges/PrivilegesItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentAccessControl(): AccessControl
	{
		$control = $this->accessControl;
		$control->templateFactory = __DIR__ . '/../../../UI/Customize/Access/Access.latte';
		$control->templateItems = __DIR__ . '/../../../UI/Customize/Access/AccessItems.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}
}
