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


/**
 * Class for handling user registration.
 * Creates and processes the registration form, inserting user data into the database.
 */
class UserSingUpFactory
{
	/** @var int|null User's role ID */
	public ?int $roleId = null;

	/** @var int|null User's ID (if already exists) */
	public ?int $userId = null;


	/**
	 * Constructor for the class.
	 *
	 * @param Connection $db     Database connection.
	 * @param Passwords $password Password hashing service.
	 * @param Factory $factory   Form factory instance.
	 */
	public function __construct(
		private readonly Connection $db,
		private readonly Passwords $password,
		private readonly Factory $factory,
	) {
	}


	/**
	 * Creates the user registration form.
	 *
	 * @return Form Registration form.
	 */
	public function create(): Form
	{
		$form = $this->factory->create();

		$form->addText(UserData::ColumnUsername, 'Username')
			->setHtmlAttribute('placeholder', 'Full name')
			->setRequired();

		$form->addText(UserData::ColumnEmail, 'Email address')
			->setHtmlAttribute('placeholder', 'Email address')
			->setDefaultValue('@')
			->setHtmlType('email')
			->addRule($form::Email)
			->setRequired();

		$form->addPassword(UserData::ColumnPassword, 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6)
			->setRequired();

		$form->addPassword(UserData::Verify, 'Password to check')
			->setHtmlAttribute('placeholder', 'Your password')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired();

		$form->addSubmit('send', 'Sign up');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * Handles the successful submission of the form.
	 * Hashes the password, generates a token, and inserts the user into the database.
	 *
	 * @param Form $form Submitted form.
	 * @param UserData $data User data from the form.
	 *
	 * @throws Exception
	 * @throws UserDuplicateEmailException
	 * @throws AssertionException
	 */
	public function success(Form $form, UserData $data): void
	{
		// Hash the password
		$data->password = $this->password->hash($data->password);

		// Generate a token
		$data->token = Random::generate(60);

		// Remove the password confirmation field
		$data->offsetUnset(UserData::Verify);

		// Validate the email format
		Validators::assert($data->email, 'email');

		try {
			// Insert the user data into the database
			$this->db->insert(UsersEntity::Table, $data->toArray())->execute();
			$this->db->insert(AccessRolesEntity::Table, [
				AccessRolesEntity::ColumnUserId => $this->userId ?? $this->db->getInsertId(),
				AccessRolesEntity::ColumnRoleId => $this->roleId ?? 2,
			])->execute();

		} catch (UniqueConstraintViolationException $e) {
			// If a unique constraint violation occurs (duplicate email), throw an exception
			throw new UserDuplicateEmailException();
		}
	}
}
