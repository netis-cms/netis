<?php

declare(strict_types=1);

namespace App\Core\Permission;

use Dibi\Result;
use Drago\Application\UI\Alert;
use Drago\Application\UI\ExtraControl;
use Drago\Component\Component;
use Drago\Component\ModalHandle;
use Drago\Component\OffcanvasHandle;
use Drago\Datagrid\DataGrid;
use Nette\Application\Attributes\Parameter;
use Nette\Application\Attributes\Requires;
use Nette\Application\UI\Form;
use Nette\Application\UI\Template;
use Nette\Forms\Controls\SubmitButton;


/**
 * Base control.
 * @property-read BaseTemplate $template
 */
abstract class BaseControl extends ExtraControl implements OffcanvasHandle, ModalHandle
{
	use Component;

	#[Parameter]
	public ?int $id = null;
	public ?string $deleteTitle = null;
	protected string $snippetMessage = 'message';


	abstract protected function getResultRepository(int $id): Result|int|null;


	abstract protected function getItemRepository(int $id): string|null;


	public function __construct(
		public Factory $factory,
	) {
	}


	public function createRender(): Template
	{
		$template = $this->template;
		$template->setTranslator($this->translator);
		$template->offcanvasId = $this->getUniqueIdComponent(self::Offcanvas);
		$template->modalId = $this->getUniqueIdComponent(self::Modal);
		$template->deleteTitle = $this->deleteTitle;
		return $template;
	}


	#[Requires(ajax: true)]
	public function handleOpenModal(): void
	{
		$this->redrawModal();
	}


	#[Requires(ajax: true)]
	public function handleOpenOffcanvas(): void
	{
		$this->redrawOffCanvas();
	}


	#[Requires(ajax: true)]
	public function handleDelete(int $id): void
	{
		$this->id = $id;
		$item = $this->getItemRepository($id);
		$item ?: $this->error();

		$this->deleteTitle = $item;
		$this->redrawControl('content');
		$this->redrawModal();
	}


	public function redrawOffCanvas(): void
	{
		$this->offCanvasComponent(self::Offcanvas);
		$this->redrawControl();
	}


	public function redrawModal(): void
	{
		$this->modalComponent(self::Modal);
		$this->redrawControl();
	}


	public function redrawFlashMessage(string $message, string $type = 'info'): void
	{
		$this->getPresenter()->flashMessage($message, $type);
		$this->getPresenter()->redrawControl($this->snippetMessage);
	}


	/** Factory for delete item form. */
	protected function createComponentDelete(): Form
	{
		$form = $this->factory->createDelete($this->id);
		$form->addSubmit('confirm', 'Confirm')
			->onClick[] = $this->delete(...);

		return $form;
	}


	private function delete(SubmitButton $button): void
	{
		$form = $button->getForm();

		try {
			$id = $form->getValues()['id'];
			$this->getResultRepository($id);

			$message = 'Delete successful.';
			$this->redrawFlashMessage($message, Alert::Success);

			$this->closeComponent();
			$this->redrawControl();

			$dataGrid = $this->getComponent('dataGrid');

			if ($dataGrid instanceof DataGrid) {
				$dataGrid->redrawDataGrid();
			}

		} catch (\Throwable $e) {
			$message = 'Unknown status code.';
			$this->redrawFlashMessage($message, Alert::Warning);
		}
	}
}
