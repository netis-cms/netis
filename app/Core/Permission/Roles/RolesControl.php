<?php

declare(strict_types=1);

namespace App\Core\Permission\Roles;

use App\Core\Permission\BaseControl;
use App\Core\Permission\Factory;
use Dibi\Exception;
use Dibi\Result;
use Drago\Application\UI\Alert;
use Drago\Attr\AttributeDetectionException;
use Drago\Datagrid\DataGrid;
use Drago\Datagrid\Exception\InvalidColumnException;
use Drago\Form\Autocomplete;
use Drago\Permission\Role;
use Nette\Application\Attributes\Requires;
use Nette\Application\UI\Form;


/** Roles control. */
class RolesControl extends BaseControl
{
	public string $permissionsDestination = 'permissions';


	public function __construct(
		public Factory $factory,
		private readonly RolesRepository $rolesRepository,
	) {
		parent::__construct($this->factory);
	}


	/** @throws AttributeDetectionException|InvalidColumnException */
	protected function createComponentDataGrid(): DataGrid
	{
		$grid = new DataGrid;
		$grid->setTranslator($this->translator);
		$grid->setRowClickAction('edit!');
		$grid->setDataSource($this->rolesRepository->getRolesFluent())
			->setPrimaryKey('id');

		$grid->addColumnText('description', 'Role description')
			->setFilterText();

		$grid->addColumnText('name', 'System name')
			->setFilterText();

		$user = $this->getPresenter()->getUser();

		if ($user->isAllowed('Backend:AccessControl', 'roles-write')) {
			$grid->addAction(
				label: 'Edit',
				signal: 'edit!',
				class: 'ajax btn btn-xs btn-primary',
				callback: fn(int $id) => $this->handleEdit($id),
			);

			$grid->addAction(
				label: 'Delete',
				signal: 'delete!',
				class: 'ajax btn btn-xs btn-danger',
				callback: fn(int $id) => $this->handleDelete($id),
			);

			$grid->addAction(
				label: 'Permissions',
				signal: 'permissions!',
				class: 'btn btn-xs btn-secondary',
				callback: fn(int $id) => $this->handlePermissions($id),
			);
		}

		return $grid;
	}


	/** Renders the control. */
	public function render(): void
	{
		$template = $this->createRender();
		$template->setFile(__DIR__ . '/Roles.latte');
		$template->render();
	}


	/** Creates roles form. */
	protected function createComponentRoles(): Form
	{
		$form = $this->factory->create();
		$form->addTextInput(RolesValues::Name, 'Role name')
			->setPlaceholder('e.g. member, editor')
			->setRequired('Please enter role name.')
			->addRule($form::Pattern, 'Only lowercase letters, numbers and hyphens.', '[a-z0-9-]+')
			->setAutocomplete(Autocomplete::Off);

		$form->addTextInput(RolesValues::Description, 'Description role')
			->setPlaceholder('Description role')
			->setRequired('Please enter description role.')
			->setAutocomplete(Autocomplete::Off);

		$form->addHidden('id', $this->id)
			->addRule($form::Integer)
			->setNullable();

		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	private function success(Form $form, RolesValues $values): void
	{
		try {
			if ((int) $values->id > 0) {
				$original = $this->rolesRepository->get((int) $values->id)->record();
				if ($original !== null && $this->isSystemRole($original->name)) {
					$values->name = $original->name;
				}
			}

			$message = (int) $values->id > 0 ? 'Update successful.' : 'Insert successful.';

			$this->rolesRepository->save($values);
			$this->redrawFlashMessage($message, Alert::Success);

			$form->reset();
			$this->closeComponent();
			$this->redrawControl();
			$this['dataGrid']->redrawDataGrid();

		} catch (\Throwable $e) {
			$message = match ($e->getCode()) {
				1 => $e->getMessage(),
				1062 => 'This role already exists.',
				default => 'Unknown status code.',
			};

			$form->addError($message);
			$this->redrawOffCanvas();
		}
	}


	/** Redirects to permissions. */
	public function handlePermissions(int $id): void
	{
		$this->getPresenter()->redirect($this->permissionsDestination, [
			'authorization-roleId' => $id,
		]);
	}


	/**
	 * Handles roles edit.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	#[Requires(ajax: true)]
	public function handleEdit(int $id): void
	{
		$items = $this->rolesRepository->get($id)->record();
		if ($items === null) {
			$this->error();
		}

		$factory = $this->getComponent('roles');
		$factory->setDefaults((array) $items);

		if ($this->isSystemRole($items->name)) {
			$nameControl = $this->getFormComponent($factory, RolesValues::Name);
			$nameControl?->setHtmlAttribute('readonly');
		}

		$sendControl = $this->getFormComponent($factory, 'send');
		$sendControl?->setCaption('Edit roles');

		$this->redrawOffCanvas();
	}


	/**
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	protected function getResultRepository(int $id): Result|int|null
	{
		return $this->rolesRepository
			->delete(RolesEntity::PrimaryKey, $id)
			->execute();
	}


	/**
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	protected function getItemRepository(int $id): string|null
	{
		$role = $this->rolesRepository->get($id)->record();

		if ($role !== null && $this->isSystemRole($role->name)) {
			$this->error('System role cannot be deleted.', 403);
		}

		return $role?->description;
	}


	private function isSystemRole(string $name): bool
	{
		return in_array($name, [Role::RoleAdmin, Role::RoleUser, Role::RoleGuest], true);
	}
}
