<?php

namespace Entity;

/**
 * @property int $categoryId
 * @property string $category
 */
class MenuCategoryEntity extends \Drago\Database\Entity
{
	const TABLE = 'menu_category';
	const CATEGORY_ID = 'categoryId';
	const CATEGORY = 'category';

	/** @var int */
	public $categoryId;

	/** @var string */
	public $category;


	public function getCategoryId(): ?int
	{
		return $this->categoryId;
	}


	public function setCategoryId(int $var)
	{
		$this['categoryId'] = $var;
	}


	public function getCategory(): string
	{
		return $this->category;
	}


	public function setCategory(string $var)
	{
		$this['category'] = $var;
	}
}
