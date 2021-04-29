<?php

declare(strict_types=1);

namespace Repository;

use DateTimeImmutable;
use Dibi\Exception;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;


class AuthRepository implements Authenticator
{
	private UsersRepository $usersRepository;
	private Passwords $password;


	public function __construct(UsersRepository $usersRepository, Passwords $password)
	{
		$this->usersRepository = $usersRepository;
		$this->password = $password;
	}


	/**
	 * @throws AuthenticationException
	 * @throws Exception
	 */
	public function authenticate(string $email, string $password): SimpleIdentity
	{
		// Find user.
		$repository = $this->usersRepository;
		$user = $repository->find($email);

		// User not found.
		if (!$user) {
			throw new AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

		// Invalid password.
		} elseif (!$this->password->verify($password, $user->password)) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);


		// Re-hash password.
		} elseif ($this->password->needsRehash($user->password)) {
			$user->password = $this->password->hash($password);
			$repository->put($user->toArray());
		}
		$user->offsetUnset('password');
		return new SimpleIdentity($user->id, null, $user);
	}
}
