<?php

declare(strict_types=1);

namespace App\Core\Settings;

use Drago\Database\Entity;


/** Database entity for settings table. */
class SettingsEntity extends Entity
{
	public const string
		Table = 'settings',
		ColumnName = 'name',
		ColumnValue = 'value';

	public string $name;
	public string $value;


	public function __construct(string $name, string $value)
	{
		parent::__construct();
		$this->name = $name;
		$this->value = $value;
	}
}
