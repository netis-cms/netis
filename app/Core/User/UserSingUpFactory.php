<?php

declare(strict_types=1);

namespace App\Core\User;

use App\Core\Factory;
use Dibi\Connection;
use Dibi\Exception;
use Dibi\UniqueConstraintViolationException;
use Drago\Authorization\Control\Access\AccessRolesEntity;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Utils\AssertionException;
use Nette\Utils\Random;
use Nette\Utils\Validators;


class UserSingUpFactory
{
	public ?int $roleId = null;
	public ?int $userId = null;

	public function __construct(
		private readonly Connection $db,
		private readonly Passwords $password,
		private readonly Factory $factory,
	) {
	}


	public function create(): Form
	{
		$form = $this->factory->create();
		$form->addText(UserData::ColumnUsername, 'Username')
			->setRequired();

		$form->addText(UserData::ColumnEmail, 'Email address')
			->setDefaultValue('@')
			->setHtmlType('email')
			->addRule($form::Email)
			->setRequired();

		$form->addPassword(UserData::ColumnPassword, 'Password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6)
			->setRequired();

		$form->addPassword(UserData::Verify, 'Password to check')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired();

		$form->addSubmit('send', 'Sign up');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Exception
	 * @throws UserDuplicateEmailException
	 * @throws AssertionException
	 */
	public function success(Form $form, UserData $data): void
	{
		$data->password = $this->password->hash($data->password);
		$data->token = Random::generate(60);
		$data->offsetUnset(UserData::Verify);

		// Validate the email format.
		Validators::assert($data->email, 'email');

		try {
			// Insert records into the database.
			$this->db->insert(UsersEntity::Table, $data->toArray())->execute();
			$this->db->insert(AccessRolesEntity::Table, [
				AccessRolesEntity::ColumnUserId => $this->userId ?? $this->db->getInsertId(),
				AccessRolesEntity::ColumnRoleId => $this->roleId ?? 2,
			])->execute();

		} catch (UniqueConstraintViolationException $e) {
			throw new UserDuplicateEmailException();
		}
	}
}
