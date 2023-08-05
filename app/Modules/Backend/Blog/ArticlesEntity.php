<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use DateTimeImmutable;
use Drago\Database\Entity;
use Nette\SmartObject;


class ArticlesEntity extends Entity
{
	use SmartObject;

	public const TABLE = 'articles';
	public const PRIMARY = 'id';
	public const TITLE = 'title';
	public const CONTENT = 'content';
	public const CATEGORY_ID = 'category_id';
	public const AUTHOR_ID = 'author_id';
	public const CREATED_AT = 'created_at';

	public string $title;
	public string $content;
	public int $category_id;
	public int $author_id;
	public DateTimeImmutable $created_at;
}
