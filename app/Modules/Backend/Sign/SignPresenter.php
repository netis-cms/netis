<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

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
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect(':Backend:Admin:');
		}
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
			$this->getUser()->login($values->email, $values->password);
			$this->redirect(':Backend:Admin:');

		} catch (AuthenticationException $e) {
			if ($e->getCode()) {
				$message = match ($e->getCode()) {
					1 => 'User not found.',
					2 => 'The password is incorrect.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
		}
	}


	/**
	 * Logout user from application.
	 * @throws AbortException
	 */
	public function actionOut(): void
	{
		$this->getUser()->logout();
		$this->redirect(':Backend:Sign:in');
	}
}
