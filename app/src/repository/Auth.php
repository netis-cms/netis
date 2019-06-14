<?php

declare(strict_types = 1);

namespace Repository;

use Dibi;
use Entity\UserEntity;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;


/**
 * User authentication.
 */
class AuthRepository implements IAuthenticator
{
	/** @var UserRepository */
	private $userRepository;

	/** @var Passwords */
	private $password;


	public function __construct(UserRepository $userRepository, Passwords $password)
	{
		$this->userRepository = $userRepository;
		$this->password = $password;
	}


	/**
	 * @throws AuthenticationException
	 * @throws Dibi\Exception
	 */
	public function authenticate(array $credentials): IIdentity
	{
		[$email, $password] = $credentials;

		// Find user.
		$row = $this
			->userRepository
			->FindByEmail($email);

		// User not found.
		if (!$row) {
			throw new AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

			// Invalid password.
		} elseif (!$this->password->verify($password, $row->password)) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);


			// Re-hash password.
		} elseif ($this->password->needsRehash($row->password)) {
			$entity = $row;
			$entity->setPassword($this->password->hash($password));

			// Save update record.
			$this->userRepository->saveUser($entity);
		}
		unset($row->password);
		return new Identity($row->userId, null, $row);
	}
}
