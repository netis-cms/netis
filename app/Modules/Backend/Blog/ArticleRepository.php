<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Repository;


#[Table(ArticleEntity::TABLE, ArticleEntity::PRIMARY)]
class ArticleRepository
{
	use Repository;

	public function __construct(
		protected Connection $db,
	) {
	}
}
