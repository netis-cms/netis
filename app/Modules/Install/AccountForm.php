<?php

declare(strict_types=1);

namespace App\Modules\Install;

use App\Modules\Backend\Sign\AccountData;
use App\Modules\BaseFactory;
use Nette\Application\UI\Form;


class AccountForm
{
	public function __construct(
		private readonly BaseFactory $baseFactory,
	) {
	}


	public function create(): Form
	{
		$form = $this->baseFactory->create();
		$form->addText(AccountData::Username, 'Username')
			->setRequired();

		$form->addText(AccountData::Email, 'Email address')
			->setDefaultValue('@')
			->setHtmlType('email')
			->addRule($form::Email)
			->setRequired();

		$form->addPassword(AccountData::Password, 'Password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6)
			->setRequired();

		$form->addPassword(AccountData::Verify, 'Password to check')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired();

		$form->addSubmit('send', 'Register');
		return $form;
	}
}
