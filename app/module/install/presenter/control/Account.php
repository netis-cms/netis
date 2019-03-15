<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */

namespace Module\Install\Control;

use Drago;
use Drago\Http;

use Nette\Security;
use Nette\Application\UI;
use Module\Install\Service;

/**
 * Add administrator account.
 */
final class Account extends Drago\Application\UI\Control
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
		$template->setFile(__DIR__ . '/../templates/Control.account.latte');
		$template->setTranslator($this->translation);
		$template->form = $this['account'];
		$template->render();
	}


	/**
	 * @return UI\Form
	 */
	public function createComponentAccount()
	{
		$form = $this->createForm();
		$form->setTranslator($this->translation);

		$form->addText('realname', 'form.name.acc')
			->setRequired('form.required');

		$form->addText('email', 'form.email')
			->setDefaultValue('@')
			->setType('email')
			->setRequired('form.required')
			->addRule(UI\Form::EMAIL, 'form.email.rule');

		$form->addPassword('password', 'form.password')
			->setRequired('form.required')
			->addRule(UI\Form::MIN_LENGTH, 'form.password.rule', 6);

		$form->addPassword('verify', 'form.password.verify')
			->setRequired('form.required')
			->addRule(UI\Form::EQUAL, 'form.password.verify.rule', $form['password']);

		$form->addSubmit('send', 'form.send.acc');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(UI\Form $form)
	{
		$values = $form->getValues();
		$table = $this->sessions->getSessionSection()->prefix . 'users';

		// Hash password.
		$values->password = Security\Passwords::hash($values->password);

		// Undo unneeded values.
		unset($values->verify, $values->prefix);

		// Insert records into the database.
		$this->query->addRecord($table, $values);

		// Save the installation step.
		$this->steps->cache->save(Service\Steps::STEP, ['step' => 5]);
		$this->flashMessage('message.acc', 'success');
	}
}
