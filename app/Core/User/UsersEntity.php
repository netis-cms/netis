<?php

/**
 * This file was generated by Drago Generator.
 */

declare(strict_types=1);

namespace App\Core\User;

use Drago\Database\Entity;


class UsersEntity extends Entity
{
	public const Table = 'users';
	public const PrimaryKey = 'id';
	public const ColumnUsername = 'username';
	public const ColumnEmail = 'email';
	public const ColumnPassword = 'password';
	public const ColumnToken = 'token';

	public int $id;
	public string $username;
	public string $email;
	public string $password;
	public string $token;
}
