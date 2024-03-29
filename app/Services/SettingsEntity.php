<?php

/**
 * This file was generated by Drago Generator.
 */

declare(strict_types=1);

namespace App\Services;

use Drago\Database\Entity;


class SettingsEntity extends Entity
{
	public const Table = 'settings';
	public const ColumnName = 'name';
	public const ColumnValue = 'value';

	public string $name;
	public string $value;
}
