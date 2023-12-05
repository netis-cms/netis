<?php

declare(strict_types=1);

namespace App\Modules\Install;

use Drago\Utils\ExtraArrayHash;


class WebsiteData extends ExtraArrayHash
{
	public const Website = 'website';
	public const Description = 'description';

	public string $website;
	public string $description;
}
