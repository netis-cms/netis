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
		$control->templateControl = __DIR__ . '/../../../UI/Customize/Permissions.latte';
		$control->templateGrid = __DIR__ . '/../../../UI/Widgets/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentRolesControl(): RolesControl
	{
		$control = $this->rolesControl;
		$control->templateControl = __DIR__ . '/../../../UI/Customize/Roles.latte';
		$control->templateGrid = __DIR__ . '/../../../UI/Widgets/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentResourcesControl(): ResourcesControl
	{
		$control = $this->resourcesControl;
		$control->translator = $this->getTranslator();
		$control->templateControl = __DIR__ . '/../../../UI/Customize/Resources.latte';
		$control->templateGrid = __DIR__ . '/../../../UI/Widgets/@grid.latte';
		return $control;
	}


	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		$control = $this->privilegesControl;
		$control->templateControl = __DIR__ . '/../../../UI/Customize/Privileges.latte';
		$control->templateGrid = __DIR__ . '/../../../UI/Widgets/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	protected function createComponentAccessControl(): AccessControl
	{
		$control = $this->accessControl;
		$control->templateControl = __DIR__ . '/../../../UI/Customize/Access.latte';
		$control->templateGrid = __DIR__ . '/../../../UI/Widgets/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}
}
