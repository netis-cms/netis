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

		$user = $this->user;
		if ($user->isLoggedIn()) {
			$this->redirect(':Admin:Admin:');
		} else {
			if ($user->getIdentity() !== null) {
				$welcome = 'Welcome back';
				$email = $user->getIdentity()
					->getData()['email'];
			}
		}

		$gravatar = $this->gravatar;
		$gravatar->setEmail($email ?? 'someone@somewhere.com');
		$gravatar->setSize(100);

		$this->template->welcome = $welcome ?? 'Welcome';
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

		$form->addText('email', 'Email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'Email address')
			->setRequired()
			->addRule(Form::EMAIL);

		$form->addPassword('password', 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->setRequired();

		$form->addSubmit('send');
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
			if ($e->getCode()) {
				$message = match ($e->getCode()) {
					1 => 'User not found.',
					2 => 'The password is incorrect.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}

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
