<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Module\Install\Control;

use Drago;
use Drago\Http;

use Module\Install\Service;
use Nette\Application\UI;

/**
 * Website settings.
 */
final class Website extends Drago\Application\UI\Control
{
	use Drago\Application\UI\Factory;
	use Drago\Localization\TranslateControl;

	/**
	 * @var Http\Sessions
	 */
	private $sessions;

	/**
	 * @var Service\Steps
	 */
	private $steps;

	/**
	 * @var Service\Query
	 */
	private $query;

	public function __construct(
		Http\Sessions $sessions,
		Service\Steps $steps,
		Service\Query $query)
	{
		parent::__construct();
		$this->sessions = $sessions;
		$this->steps = $steps;
		$this->query = $query;
	}

	public function render()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.website.latte');
		$template->setTranslator($this->translation);
		$template->form = $this['website'];
		$template->render();
	}

	/**
	 * @return UI\Form
	 */
	public function createComponentWebsite()
	{
		$form = $this->createForm();
		$form->setTranslator($this->translation);

		$form->addText('website', 'form.name.web')
			->setRequired('form.required');

		$form->addText('description', 'form.description')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.web');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}

	public function success(UI\Form $form)
	{
		$values = $form->getValues();
		$table  = $this->sessions->getSessionSection()->prefix . 'settings';
		$settings = [
			['name' => 'website',     'value' => $values->website],
			['name' => 'description', 'value' => $values->description],
		];

		// Insert records into the database.
		foreach ($settings as $rows) {
			$this->query->addRecord($table, $rows);
		}

		// Save the installation step.
		$this->steps->cache->save(Service\Steps::STEP, ['step' => 4]);
		$this->flashMessage('message.web', 'success');
	}

}
