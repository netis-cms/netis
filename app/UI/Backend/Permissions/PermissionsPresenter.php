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
	 * Generic method to create components with similar structure.
	 *
	 * @throws Exception
	 */
	private function createAuthorizationControl(string $controlName, string $templateControl): object
	{
		// Get the correct control based on the control name.
		$control = $this->{$controlName};

		// Set common properties for the controls.
		$control->templateControl = __DIR__ . '/Control/' . $templateControl;
		$control->templateGrid = __DIR__ . '/../../../Core/Widget/@grid.latte';
		$control->translator = $this->getTranslator();

		return $control;
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		return $this->createAuthorizationControl('permissionsControl', 'custom.permissions.latte');
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentRolesControl(): RolesControl
	{
		return $this->createAuthorizationControl('rolesControl', 'custom.roles.latte');
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentResourcesControl(): ResourcesControl
	{
		return $this->createAuthorizationControl('resourcesControl', 'custom.resources.latte');
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		return $this->createAuthorizationControl('privilegesControl', 'custom.privileges.latte');
	}


	/**
	 * @throws Exception
	 */
	protected function createComponentAccessControl(): AccessControl
	{
		return $this->createAuthorizationControl('accessControl', 'custom.access.latte');
	}
}
