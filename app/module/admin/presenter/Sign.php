<?php

namespace Module\Admin;

use Nette;
use Nette\Application\UI\Form;
use App\DashboardPresenter;
use Supplement\Gravatar;


/**
 * Sing-in user.
 */
final class SignPresenter extends DashboardPresenter
{
	/**
	 * @var Gravatar
	 * @inject
	 */
	public $gravatar;

	protected function startup(): void
	{
		parent::startup();
		$this->setTemplate('sign', 'dev.sign');
	}


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$user = $this->user->identity;
		if ($user) {
			$welcome = 'login.welcome.back';
			$email = $user->data['email'];
		}
		$this->template->welcome = isset($welcome) ? $welcome : 'login.welcome';
		$this->template->gravatar = $this->gravatar->getGravatar(isset($email) ? $email : '', 120);
	}


	public function renderIn(): void
	{
		$this->template->form = $this['signIn'];
		if($this->isAjax()) {
			$this->redrawControl('sign-in');
		}
	}


	protected function createComponentSignIn(): Form
	{
		$form = $this->createForm();
		$form->setTranslator($this->getTranslator());

		$form->addText('email', 'form.email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'form.email.full')
			->setRequired('form.required')
			->addRule(Form::EMAIL, 'form.email.rule');

		$form->addPassword('password', 'form.password')
			->setHtmlAttribute('placeholder', 'form.password.full')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.login');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Nette\Application\AbortException
	 */
	public function success(Form $form, $values): void
	{
		try {
			$this->user->login($values->email, $values->password);
			$this->redirect(':Admin:Admin:');

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('form.error.' . $e->getCode());
			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}


	/**
	 * Logout user from application.
	 * @throws Nette\Application\AbortException
	 */
	public function actionOut()
	{
		$this->user->logout();
		$this->redirect(':Admin:Sign:in');
	}
}
