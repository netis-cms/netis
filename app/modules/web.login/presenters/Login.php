<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Admin\Module;

use Base;
use Supplement;

use Nette\Application\UI;
use Nette\Security;

/**
 * Sing-in users.
 */
final class LoginPresenter extends Base\BasePresenter
{
	/**
	 * Store a restore requests.
	 * @persistent
	 */
	public $backlink;

	/**
	 * @var Supplement\Gravatar
	 * @inject
	 */
	public $gravatar;

	/**
	 * @return array
	 */
	private function getTranslator()
	{
		return $this->translator('web.login');
	}

	// Setup rendering.
	protected function beforeRender()
	{
		parent::beforeRender();
		$user = $this->user->identity;
		if ($user) {
			$welcome  = 'login.welcome.back';
			$gravatar = $user->data['email'];
		}
		$this->template->setTranslator($this->getTranslator());
		$this->template->welcome  = isset($welcome) ? $welcome : 'login.welcome';
		$this->template->gravatar = $this->gravatar->getGravatar(isset($gravatar) ? $gravatar : NULL, 100);
	}

	/**
	 * @return UI\Form
	 */
	protected function createComponentSignIn()
	{
		$form = $this->createForm();
		$form->setTranslator($this->getTranslator());

		$form->addText('email', 'login.email')
			->setType('email')
			->setRequired('login.empty')
			->setAttribute('placeholder', 'login.email.place')
			->addRule(UI\Form::EMAIL, 'login.email.invalid');

		$form->addPassword('password', 'login.pass')
			->setAttribute('placeholder', 'login.pass.place')
			->setRequired('login.empty');

		$form->addSubmit('send', 'login.send');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}

	// Form process.
	public function success(UI\Form $form, $values)
	{
		try {
			$this->user->login($values->email, $values->password);
			$this->restoreRequest($this->backlink);
			$this->redirect(':Web:Web:');

		} catch (Security\AuthenticationException $e) {
			if ($e->getCode() == 1) {
				$form->addError('login.user.invalid');

			} elseif ($e->getCode() == 2) {
				$form->addError('login.pass.invalid');
			}
		}
	}

	// Logout user.
	public function actionOut()
	{
		$this->user->logout();
		$this->redirect(':Admin:Login:');
	}

}
