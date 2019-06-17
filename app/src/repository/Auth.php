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
		$repository = $this->userRepository;
		$user = $repository->findBy($email);

		// User not found.
		if (!$user) {
			throw new AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

			// Invalid password.
		} elseif (!$this->password->verify($password, $user->password)) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);


			// Re-hash password.
		} elseif ($this->password->needsRehash($user->password)) {
			$user->setPassword($this->password->hash($password));
			$repository->save($user);
		}
		unset($user->password);
		return new Identity($user->userId, null, $user);
	}
}
