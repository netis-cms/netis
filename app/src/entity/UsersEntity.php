<?php

namespace Entity;

/**
 * @property int $userId
 * @property string $realname
 * @property string $email
 * @property string $password
 */
class UsersEntity extends \Drago\Database\Entity
{
	const TABLE = 'users';
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


	public function getUserId(): ?int
	{
		return $this->userId;
	}


	public function setUserId(int $var)
	{
		$this['userId'] = $var;
	}


	public function getRealname(): string
	{
		return $this->realname;
	}


	public function setRealname(string $var)
	{
		$this['realname'] = $var;
	}


	public function getEmail(): string
	{
		return $this->email;
	}


	public function setEmail(string $var)
	{
		$this['email'] = $var;
	}


	public function getPassword(): string
	{
		return $this->password;
	}


	public function setPassword(string $var)
	{
		$this['password'] = $var;
	}
}
