<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Repository;

use Nette\Security;
use Entity;

/**
 * User authentication.
 */
class Auth implements Security\IAuthenticator
{
	/**
	 * @var User
	 */
	private $repositoryUser;

	/**
	 * @var Entity\User
	 */
	private $entityUser;

	public function __construct(User $repositoryUser, Entity\User $entityUser)
	{
		$this->repositoryUser = $repositoryUser;
		$this->entityUser = $entityUser;
	}

	/**
	 * @return Security\Identity
	 * @throws Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		// Find user.
		$row = $this->repositoryUser->find($email);

		// Invalid username.
		if (!$row) {
			throw new Security\AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

		// Invalid password.
		} elseif (!Security\Passwords::verify($password, $row->password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		// Re-hash password.
		} elseif (Security\Passwords::needsRehash($row->password)) {
			$entity = $this->entityUser;
			$entity->setId($row->id);
			$entity->password = Security\Passwords::hash($password);
			$this->repositoryUser->save($entity);
		}
		unset($row->password);
		return new Security\Identity($row->userId, null, $row->toArray());
	}

}
