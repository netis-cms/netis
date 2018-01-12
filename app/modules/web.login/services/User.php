<?php

/**
 * Netis, Little CMS
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Login\Service;

use Login;
use Nette\Security;

/**
 * Users authentication.
 */
class User implements Security\IAuthenticator
{
	/**
	 * @var Login\Repository
	 */
	private $repository;

	/**
	 * @var Login\Entity
	 */
	private $entity;

	public function __construct(
		Login\Repository $repository,
		Login\Entity $entity)
	{
		$this->repository = $repository;
		$this->entity = $entity;
	}

	/**
	 * @param array
	 * @return Security\Identity
	 * @throws Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		// Find user.
		$row = $this->repository->find($email);

		// Invalid username.
		if (!$row) {
			throw new Security\AuthenticationException('User not found.', self::IDENTITY_NOT_FOUND);

		// Invalid password.
		} elseif (!Security\Passwords::verify($password, $row->password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		// Re-hash password.
		} elseif (Security\Passwords::needsRehash($row->password)) {

			$entity = $this->entity;
			$entity->setId($row->id);
			$entity->password = Security\Passwords::hash($password);
			$this->repository->save($entity);
		}
		unset($row->password);
		return new Security\Identity($row->userId, NULL, $row->toArray());
	}

}
