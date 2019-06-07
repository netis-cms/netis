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
}
