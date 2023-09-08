<?php

declare(strict_types=1);

namespace App\Services\Repository;

use App\Services\Entity\SettingsEntity;
use Dibi\Connection;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Repository;
use Nette\SmartObject;


#[Table(SettingsEntity::table)]
class SettingsRepository
{
	use SmartObject;
	use Repository;

	public function __construct(
		protected Connection $db,
	) {
	}


	/**
	 * @throws AttributeDetectionException
	 */
	public function getSettings(): array
	{
		return $this->all()
			->fetchPairs(SettingsEntity::name, SettingsEntity::value);
	}
}
