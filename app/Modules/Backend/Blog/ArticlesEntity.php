<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Drago\Database\Entity;


class ArticlesEntity extends Entity
{
	use ArticlesMapper;

	public const Table = 'articles';
	public const PrimaryKey = 'id';
	public const ColumnTitle = 'title';
	public const ColumnContent = 'content';
	public const ColumnCategoryId = 'category_id';
	public const ColumnAuthorId = 'author_id';
	public const ColumnCreatedAt = 'created_at';
}
