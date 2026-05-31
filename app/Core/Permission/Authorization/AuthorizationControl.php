<?php

declare(strict_types=1);

namespace App\Core\Permission\Authorization;

use App\Core\Permission\BaseControl;
use App\Core\Permission\Factory;
use App\Core\Permission\Roles\RolesRepository;
use Dibi\Exception;
use Dibi\Result;
use Dibi\Row;
use Drago\Attr\AttributeDetectionException;
use Drago\Permission\Role;
use Nette\Application\Attributes\Parameter;
use Nette\Application\Attributes\Requires;


/**
 * Authorization control.
 * @property-read AuthorizationTemplate $template
 */
class AuthorizationControl extends BaseControl
{
	#[Parameter]
	public ?int $roleId = null;


	public function __construct(
		public Factory $factory,
		private readonly RolesRepository $rolesRepository,
		private readonly AuthorizationRepository $authorizationRepository,
	) {
		parent::__construct($this->factory);
	}


	/**
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/Authorization.latte');
		$template->setTranslator($this->translator);
		$template->roles = $this->rolesRepository->getAllRoles();
		$template->roleId = $this->roleId ?? 0;

		if ($this->roleId !== null) {
			$role = $this->rolesRepository->get($this->roleId)->record();

			if ($role === null) {
				$template->render();
				return;
			}

			$isAdminRole = $role->name === Role::RoleAdmin;
			$template->roleName = $role->description;
			$template->isAdminRole = $isAdminRole;

			$rolePermissions = $this->authorizationRepository->getRolePermissions($this->roleId);
			$template->groupedPermissions = $this->groupPermissionsByResource($rolePermissions);

			if ($isAdminRole) {
				$template->allowedCount = count($rolePermissions);
				$template->deniedCount = 0;
			} else {
				$template->allowedCount = count(array_filter(
					$rolePermissions,
					static fn(Row $item): bool => $item['access'] === 'allow',
				));
				$template->deniedCount = count($rolePermissions) - $template->allowedCount;
			}
		}

		$template->render();
	}


	/**
	 * @param Row[] $permissions
	 * @return array<string, Row[]>
	 */
	private function groupPermissionsByResource(array $permissions): array
	{
		$groupedPermissions = [];
		foreach ($permissions as $permission) {
			$resource = (string) $permission['resource'];
			$groupedPermissions[$resource][] = $permission;
		}

		return $groupedPermissions;
	}


	/**
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	#[Requires(ajax: true)]
	public function handleTogglePermission(int $roleId, int $resourceId): void
	{
		$allowed = (int) $this->getPresenter()
			->getHttpRequest()
			->getPost('allowed');

		if ($allowed === 1) {
			$this->authorizationRepository->allow($roleId, $resourceId);
		} else {
			$this->authorizationRepository->deny($roleId, $resourceId);
		}

		$this->roleId = $roleId;
		$this->redrawControl('permissions');
	}


	protected function getResultRepository(int $id): Result|int|null
	{
		return null;
	}


	protected function getItemRepository(int $id): string|null
	{
		return null;
	}
}
