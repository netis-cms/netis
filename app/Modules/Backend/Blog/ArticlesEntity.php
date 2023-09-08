<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Drago\Database\Entity;
use Nette\SmartObject;


class ArticlesEntity extends Entity
{
	public const Table = 'articles';
	public const Primary = 'id';
	public const Title = 'title';
	public const Content = 'content';
	public const CategoryId = 'category_id';
	public const AuthorId = 'author_id';
	public const CreatedAt = 'created_at';

	use SmartObject;
	use ArticlesMapper;
}
