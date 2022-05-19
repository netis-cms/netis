<?php

declare(strict_types=1);

namespace App\Modules\Backend\Admin;

use App\Modules\BasePresenter;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;


/**
 * Sing-in user.
 * @property-read SignTemplate $template
 */
final class SignPresenter extends BasePresenter
{
	/**
	 * @throws Exception
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();
		if ($this->user->isLoggedIn()) {
			$this->redirect(':Admin:Admin:');
		}
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
			->addRule($form::EMAIL);

		$form->addPassword('password', 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->setRequired();

		$form->addSubmit('send', 'Sign in');
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
