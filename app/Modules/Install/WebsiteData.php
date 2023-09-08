<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Utils\ExtraArrayHash;
use Nette\SmartObject;


class WebsiteData extends ExtraArrayHash
{
	use SmartObject;

	public const website = 'website';
	public const description = 'description';

	public string $website;
	public string $description;
}
