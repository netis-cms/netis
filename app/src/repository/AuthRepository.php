<?php

declare(strict_types = 1);

namespace Repository;

use Dibi;
use Nette\Security;


class AuthRepository implements Security\IAuthenticator
{
	/** @var UsersRepository */
	private $usersRepository;

	/** @var Security\Passwords */
	private $password;


	public function __construct(UsersRepository $usersRepository, Security\Passwords $password)
	{
		$this->usersRepository = $usersRepository;
		$this->password = $password;
	}


	/**
	 * @throws Security\AuthenticationException
	 * @throws Dibi\Exception
	 */
	public function authenticate(array $credentials): Security\IIdentity
	{
		[$email, $password] = $credentials;

		// Find user.
		$repository = $this->usersRepository;
		$user = $repository->findBy($email);

		// User not found.
		if (!$user) {
			throw new Security\AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

			// Invalid password.
		} elseif (!$this->password->verify($password, $user->password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);


			// Re-hash password.
		} elseif ($this->password->needsRehash($user->password)) {
			$user->setPassword($this->password->hash($password));
			$repository->save($user);
		}
		unset($user->password);
		return new Security\Identity($user->userId, null, $user);
	}
}
