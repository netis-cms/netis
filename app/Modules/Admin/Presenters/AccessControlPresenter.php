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
use Drago\Parameters\Parameters;
use Nette\DI\Attributes\Inject;


final class AccessControlPresenter extends DashboardPresenter
{
	#[Inject]
	public Parameters $dirs;


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		$control = $this->permissionsControl;
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/permissions.add.latte');
		$control->setTemplateFile(__DIR__ . '/templates/AccessControl/permissions.records.latte', 'records');
		$control->setTranslator($this->getTranslator());
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
		$control->setTranslator($this->getTranslator());
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
		$control->setTranslator($this->getTranslator());
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
		$control->setTranslator($this->getTranslator());
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
		$control->setTranslator($this->getTranslator());
		return $control;
	}
}
