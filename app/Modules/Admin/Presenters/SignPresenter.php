<?php

declare(strict_types=1);

namespace App\Modules\Admin\Presenters;

use App\Modules\BasePresenter;
use Drago\User\Gravatar;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\SimpleIdentity;


/**
 * Sing-in user.
 */
final class SignPresenter extends BasePresenter
{
	public function __construct(
		private Gravatar $gravatar,
	) {
		parent::__construct();
	}


	/**
	 * @throws Exception
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		/** @var SimpleIdentity $user */
		$user = $this->user->identity;
		if ($user !== null) {
			$welcome = 'login.welcome.back';
			$email = $user->data['email'];
		}

		$gravatar = $this->gravatar;
		$gravatar->setEmail($email ?? 'someone@somewhere.com');
		$gravatar->setSize(100);

		$this->template->welcome = $welcome ?? 'login.welcome';
		$this->template->gravatar = $this->gravatar->getGravatar();
	}


	public function renderIn(): void
	{
		$this->template->form = $this['signIn'];
	}


	protected function createComponentSignIn(): Form
	{
		$form = new Form;
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
	 * @throws AbortException
	 */
	public function success(Form $form, $values): void
	{
		try {
			$this->user->login($values->email, $values->password);
			$this->redirect(':Admin:Admin:');

		} catch (AuthenticationException $e) {
			$form->addError('form.error.' . $e->getCode());
			if ($this->isAjax()) {
				$this->redrawControl('errors');
			}
		}
	}


	/**
	 * Logout user from application.
	 * @throws AbortException
	 */
	public function actionOut(): void
	{
		$this->user->logout();
		$this->redirect(':Admin:Sign:in');
	}
}
