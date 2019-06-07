<?php

declare(strict_types = 1);

namespace Module\Install\Control;

use Dibi;
use Drago\Application\UI\Control;
use Drago\Application\UI\Factory;
use Drago\Http\Sessions;
use Drago\Localization\TranslateControl;
use Module\Install\Service\Query;
use Module\Install\Service\Steps;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;


/**
 * Add administrator account.
 */
final class Account extends Control
{
	use Factory;
	use TranslateControl;

	/** @var Sessions */
	private $sessions;

	/** @var Steps */
	private $steps;

	/** @var Query  */
	private $query;

	/** @var Passwords */
	private $password;


	public function __construct(Sessions $sessions, Steps $steps, Query $query, Passwords $password)
	{
		$this->sessions = $sessions;
		$this->steps = $steps;
		$this->query = $query;
		$this->password = $password;
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.account.latte');
		$template->setTranslator($this->getTranslator());
		$template->form = $this['account'];
		$template->render();
	}


	public function createComponentAccount(): Form
	{
		$form = $this->createForm();
		$form->setTranslator($this->getTranslator());

		$form->addText('realname', 'form.name.acc')
			->setRequired('form.required');

		$form->addText('email', 'form.email')
			->setDefaultValue('@')
			->setHtmlType('email')
			->setRequired('form.required')
			->addRule(Form::EMAIL, 'form.email.rule');

		$form->addPassword('password', 'form.password')
			->setRequired('form.required')
			->addRule(Form::MIN_LENGTH, 'form.password.rule', 6);

		$form->addPassword('verify', 'form.password.verify')
			->setRequired('form.required')
			->addRule(Form::EQUAL, 'form.password.verify.rule', $form['password']);

		$form->addSubmit('send', 'form.send.acc');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Dibi\Exception
	 */
	public function success(Form $form): void
	{
		$values = $form->getValues();
		$table = $this->sessions->getSessionSection()->prefix . 'users';

		// Hash password.
		$values->password = $this->password->hash($values->password);

		// Undo unneeded values.
		unset($values->verify, $values->prefix);

		// Insert records into the database.
		$this->query->addRecord($table, (array) $values);

		// Save the installation step.
		$this->steps->cache->save(Steps::STEP, ['step' => 5]);
		$this->presenter->flashMessage('message.acc', 'success');
	}
}
