<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Drago\Database\Entity;


class ArticlesEntity extends Entity
{
	use ArticlesMapper;

	public const Table = 'articles';
	public const Id = 'id';
	public const Title = 'title';
	public const content = 'content';
	public const CategoryId = 'category_id';
	public const AuthorId = 'author_id';
	public const CreatedAt = 'created_at';
}
