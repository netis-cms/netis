<?php

namespace Module\Admin;

use Drago\Application\UI\Factory;
use Drago\Localization\TranslatorAdapter;
use Drago\User\Gravatar;
use Nette\Application\UI;


/**
 * Sing-in user.
 */
final class SignPresenter extends UI\Presenter
{
	use TranslatorAdapter;

	/**
	 * @var Gravatar
	 * @inject
	 */
	public $gravatar;

	/**
	 * @var Factory
	 * @inject
	 */
	public $factory;


	protected function startup(): void
	{
		parent::startup();
		$this->setLayout('dev');
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$user = $this->user->identity;
		if ($user) {
			$welcome = 'login.welcome.back';
			$email = $user->data['email'];
		}

		$gravatar = $this->gravatar;
		$gravatar->setEmail($email ?? 'someone@somewhere.com');
		$gravatar->setSize(120);

		$this->template->welcome = isset($welcome) ? $welcome : 'login.welcome';
		$this->template->gravatar = $this->gravatar->getGravatar();
	}


	public function renderIn(): void
	{
		$this->template->form = $this['signIn'];
		if($this->isAjax()) {
			$this->redrawControl('sign-in');
		}
	}


	protected function createComponentSignIn(): UI\Form
	{
		$form = $this->factory->create();
		$form->setTranslator($this->getTranslator());

		$form->addText('email', 'form.email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'form.email.full')
			->setRequired('form.required')
			->addRule(UI\Form::EMAIL, 'form.email.rule');

		$form->addPassword('password', 'form.password')
			->setHtmlAttribute('placeholder', 'form.password.full')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.login');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws \Nette\Application\AbortException
	 */
	public function success(UI\Form $form, $values): void
	{
		try {
			$this->user->login($values->email, $values->password);
			$this->redirect(':Admin:Admin:');

		} catch (\Nette\Security\AuthenticationException $e) {
			$form->addError('form.error.' . $e->getCode());
			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}


	/**
	 * Logout user from application.
	 * @throws \Nette\Application\AbortException
	 */
	public function actionOut()
	{
		$this->user->logout();
		$this->redirect(':Admin:Sign:in');
	}
}
