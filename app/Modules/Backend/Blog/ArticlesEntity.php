<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Drago\Database\Entity;
use Nette\SmartObject;


class ArticlesEntity extends Entity
{
	use SmartObject;
	use ArticlesMapper;

	public const table = 'articles';
	public const id = 'id';
	public const title = 'title';
	public const content = 'content';
	public const categoryId = 'category_id';
	public const authorId = 'author_id';
	public const createdAt = 'created_at';
}
