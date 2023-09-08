<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use DateTimeImmutable;


trait ArticlesMapper
{
	public string $title;
	public string $content;
	public int $category_id;
	public int $author_id;
	public DateTimeImmutable $created_at;
}
