<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Module\Admin;

use Nette\Application\UI;
use Repository;
use Entity;
use Exception;

/**
 * Admin website settings.
 */
final class SettingsPresenter extends DashboardPresenter
{
	/**
	 * @var Repository\Settings
	 * @inject
	 */
	public $repositorySettings;

	/**
	 * @var Entity\Settings
	 * @inject
	 */
	public $entitySettings;

	/**
	 * @return UI\Form
	 */
	protected function createComponentSettings()
	{
		$form = $this->createForm();
		$form->setTranslator($this->translator());

		$form->addText('website', 'form.name')
			->setRequired('form.required');

		$form->addText('description', 'form.description')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.website');
		$form->onSuccess[] = [$this, 'process'];
		return $form;
	}

	public function process(UI\Form $form)
	{
		$values = $form->getValues();
		$entity = $this->entitySettings;
		$entity->website = $values->website;
		$entity->description = $values->description;
		try {
			$this->repositorySettings->save($entity);
			$this->flashMessage('message.settings', 'success');
			if ($this->isAjax()) {
				$this->redrawControl('message');
				$this->redrawControl('title');
				$this->redrawControl('factory');
			}

		} catch (Exception $e) {
			\Tracy\Debugger::barDump($e);
			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}

	}

	public function renderDefault()
	{
		$form = $this['settings'];
		$form->setDefaults($this->repositoryWebsite->all());
		$this->template->form = $form;
	}

}
