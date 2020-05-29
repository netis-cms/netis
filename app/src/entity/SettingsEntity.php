<?php

namespace Entity;

/**
 * @property string $name
 * @property string $value
 */
class SettingsEntity extends \Drago\Database\Entity
{
	const TABLE = 'settings';
	const NAME = 'name';
	const VALUE = 'value';

	/** @var string */
	public $name;

	/** @var string */
	public $value;


	public function getName(): string
	{
		return $this->name;
	}


	public function setName(string $var)
	{
		$this['name'] = $var;
	}


	public function getValue(): string
	{
		return $this->value;
	}


	public function setValue(string $var)
	{
		$this['value'] = $var;
	}
}
