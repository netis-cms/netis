<?php

declare(strict_types=1);

namespace Module\Admin;

use Base\BackendPresenter;
use Drago\Authorization\Control\AccessControl;
use Drago\Authorization\Control\PermissionsControl;
use Drago\Authorization\Control\PrivilegesControl;
use Drago\Authorization\Control\ResourcesControl;
use Drago\Authorization\Control\RolesControl;
use Drago\Authorization\FileNotFoundException;


final class AccessControlPresenter extends BackendPresenter
{
	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		$control = $this->permissionsControl;
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/permissions.add.latte');
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/permissions.records.latte', 'records');
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentRolesControl(): RolesControl
	{
		$control = $this->rolesControl;
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/roles.add.latte');
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/roles.records.latte', 'records');
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentResourcesControl(): ResourcesControl
	{
		$control = $this->resourcesControl;
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/resources.add.latte');
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/resources.records.latte', 'records');
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		$control = $this->privilegesControl;
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/privileges.add.latte');
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/privileges.records.latte', 'records');
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentAccessControl(): AccessControl
	{
		$control = $this->accessControl;
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/users.add.latte');
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/users.records.latte', 'records');
		return $control;
	}
}
