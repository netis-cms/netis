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
	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentPermissionsControl(): PermissionsControl
	{
		$control = $this->permissionsControl;
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Permissions/Permissions.add.latte');
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Permissions/Permissions.records.latte', 'records');
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentRolesControl(): RolesControl
	{
		$control = $this->rolesControl;
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Roles/Roles.add.latte');
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Roles/Roles.records.latte', 'records');
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentResourcesControl(): ResourcesControl
	{
		$control = $this->resourcesControl;
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Resources/Resources.add.latte');
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Resources/Resources.records.latte', 'records');
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentPrivilegesControl(): PrivilegesControl
	{
		$control = $this->privilegesControl;
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Privileges/Privileges.add.latte');
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Privileges/Privileges.records.latte', 'records');
		$control->setTranslator($this->getTranslator());
		return $control;
	}


	/**
	 * @throws FileNotFoundException
	 */
	protected function createComponentAccessControl(): AccessControl
	{
		$control = $this->accessControl;
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Access/Access.add.latte');
		$control->setTemplateFile(__DIR__ . '/../../../UI/Templates/Access/Access.records.latte', 'records');
		$control->setTranslator($this->getTranslator());
		return $control;
	}
}
