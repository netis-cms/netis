<?php

namespace Entity;

/**
 * @property int $menuId
 * @property int $categoryId
 * @property string $link
 * @property string $name
 */
class MenuEntity extends \Drago\Database\Entity
{
	const TABLE = 'menu';
	const MENU_ID = 'menuId';
	const CATEGORY_ID = 'categoryId';
	const LINK = 'link';
	const NAME = 'name';

	/** @var int */
	public $menuId;

	/** @var int */
	public $categoryId;

	/** @var string */
	public $link;

	/** @var string */
	public $name;


	public function getMenuId(): ?int
	{
		return $this->menuId;
	}


	public function setMenuId(int $var)
	{
		$this['menuId'] = $var;
	}


	public function getCategoryId(): int
	{
		return $this->categoryId;
	}


	public function setCategoryId(int $var)
	{
		$this['categoryId'] = $var;
	}


	public function getLink(): string
	{
		return $this->link;
	}


	public function setLink(string $var)
	{
		$this['link'] = $var;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function setName(string $var)
	{
		$this['name'] = $var;
	}
}
