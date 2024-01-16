<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

use App\Modules\BaseFactory;
use App\Modules\BasePresenter;
use Drago\Application\UI\Alert;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;


/**
 * Sing-in user.
 * @property SignTemplate $template
 */
final class SignPresenter extends BasePresenter
{
	public function __construct(
		private readonly BaseFactory $baseFactory,
		private readonly AccountFactory $accountFactory,
	) {
		parent::__construct();
	}


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
		$form = $this->baseFactory->create();
		$form->addText(SignData::Email, 'Email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'Email address')
			->addRule($form::EMAIL)
			->setRequired();

		$form->addPassword(SignData::Password, 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->setRequired();

		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws AbortException
	 */
	public function success(Form $form, SignData $data): void
	{
		try {
			$this->getUser()->login($data->email, $data->password);
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


	protected function createComponentRegister(): Form
	{
		$form = $this->accountFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Registration was successful.', Alert::Success);
			$this->redirect('in');
		};
		return $form;
	}


	/**
	 * Logout user from application.
	 * @throws AbortException
	 */
	public function actionUserOut(): void
	{
		$this->getUser()->logout();
		$this->redirect(':Backend:Sign:in');
	}
}
