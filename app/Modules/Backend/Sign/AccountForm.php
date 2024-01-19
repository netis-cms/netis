<?php

declare(strict_types=1);

namespace App\Modules\Backend\Sign;

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
		$form->addText(AccountData::ColumnUsername, 'Username')
			->setRequired();

		$form->addText(AccountData::ColumnEmail, 'Email address')
			->setDefaultValue('@')
			->setHtmlType('email')
			->addRule($form::Email)
			->setRequired();

		$form->addPassword(AccountData::ColumnPassword, 'Password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6)
			->setRequired();

		$form->addPassword(AccountData::Verify, 'Password to check')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired();

		$form->addSubmit('send', 'Register');
		return $form;
	}
}
