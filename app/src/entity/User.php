<?php

declare(strict_types = 1);

namespace Entity;

use Drago;


/**
 * User entity.
 */
class UserEntity extends Drago\Database\Entity
{
	const TABLE = ':prefix:users';
	const USER_ID = 'userId';
	const REALNAME = 'realname';
	const EMAIL = 'email';
	const PASSWORD = 'password';

	/** @var int */
	public $userId;

	/** @var string */
	public $realname;

	/** @var string */
	public $email;

	/** @var string */
	public $password;


	public function setUserId(int $userId)
	{
		$this['userId'] = $userId;
	}


	public function getUserId(): int
	{
		return $this->userId;
	}


	public function seRealname(string $realname)
	{
		$this['realname'] = $realname;
	}


	public function getRealname(): string
	{
		return $this->realname;
	}


	public function setEmail(string $email)
	{
		$this['email'] = $email;
	}


	public function getEmail(): string
	{
		return $this->realname;
	}


	public function setPassword(string $password)
	{
		$this['password'] = $password;
	}


	public function getPassword(): string
	{
		return $this->password;
	}
}
