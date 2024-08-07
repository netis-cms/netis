<?php

declare(strict_types=1);

namespace App\UI\Backend\Permissions;

use App\UI\Backend\BackendPresenter;
use Drago\Authorization\Control\Access\AccessControl;
use Drago\Authorization\Control\AuthorizationControl;
use Drago\Authorization\Control\Permissions\PermissionsControl;
use Drago\Authorization\Control\Privileges\PrivilegesControl;
use Drago\Authorization\Control\Resources\ResourcesControl;
use Drago\Authorization\Control\Roles\RolesControl;
use Nette\Neon\Exception;


/**
 * @property PermissionsTemplate $template
 */
final class PermissionsPresenter extends BackendPresenter
{
	use AuthorizationControl;

	/**
	 * @throws Exception
	 */
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		$control = $this->permissionsControl;
		$control->templateControl = __DIR__ . '/Control/control.permissions.latte';
		$control->templateGrid = __DIR__ . '/../../../Core/Widget/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentRolesControl(): RolesControl
	{
		$control = $this->rolesControl;
		$control->templateControl = __DIR__ . '/Control/custom.roles.latte';
		$control->templateGrid = __DIR__ . '/../../../Core/Widget/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentResourcesControl(): ResourcesControl
	{
		$control = $this->resourcesControl;
		$control->translator = $this->getTranslator();
		$control->templateControl = __DIR__ . '/Control/custom.resources.latte';
		$control->templateGrid = __DIR__ . '/../../../Core/Widget/@grid.latte';
		return $control;
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		$control = $this->privilegesControl;
		$control->templateControl = __DIR__ . '/Control/custom.privileges.latte';
		$control->templateGrid = __DIR__ . '/../../../Core/Widget/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentAccessControl(): AccessControl
	{
		$control = $this->accessControl;
		$control->templateControl = __DIR__ . '/Control/custom.access.latte';
		$control->templateGrid = __DIR__ . '/../../../Core/Widget/@grid.latte';
		$control->translator = $this->getTranslator();
		return $control;
	}
}
