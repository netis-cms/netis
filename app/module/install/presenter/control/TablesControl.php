<?php

declare(strict_types=1);

namespace Module\Install\Control;

use Dibi\Connection;
use Drago\Localization\Translator;
use Module\Install\Service\Steps;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\InvalidStateException;


/**
 * Install database tables.
 */
final class TablesControl extends Control
{
	public function __construct(
		private Connection $db,
		private Translator $translator,
		private Steps $steps,
	) {
	}


	public function render(): void
	{
		if ($this->template instanceof Template) {
			$template = $this->template;
			$template->setFile(__DIR__ . '/../templates/Control.tables.latte');
			$template->setTranslator($this->translator);
			$template->form = $this['tables'];
			$template->render();
		} else {
			throw new InvalidStateException('Control is without template.');
		}
	}


	public function createComponentTables(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->addSubmit('send', 'form.send.tables');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form): void
	{
		try {

			// Import SQL dump from file.
			$this->db->loadFile(__DIR__ . '/../../../../../assets/db.sql');

			// Save the installation step.
			$this->steps->cache->save(Steps::STEP, ['step' => 3]);
			$this->presenter->flashMessage('message.tables', 'success');

		} catch (\Exception $e) {
			if ($e->getCode()) {
				$form->addError('form.error.' . $e->getCode());
			}

			if ($this->presenter->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}
}
