<?php

declare(strict_types=1);

namespace App\UI\Install\Factory;

use App\Core\Factory;
use App\Core\Settings\SettingsEntity;
use App\UI\Install\Steps;
use Dibi\Connection;
use Dibi\Exception;
use Nette\Application\UI\Form;


/**
 * WebsiteControl settings.
 */
final class WebsiteFactory
{
	public function __construct(
		private readonly Steps $steps,
		private readonly Connection $db,
		private readonly Factory $factory,
	) {
	}


	public function create(): Form
	{
		$form = $this->factory->create();
		$form->addText(WebsiteData::Website, 'Site name')
			->setRequired();

		$form->addText(WebsiteData::Description, 'Site description')
			->setRequired();

		$form->addSubmit('send', 'Save data');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Exception
	 */
	public function success(Form $form, WebsiteData $data): void
	{
		$settings = [
			['name' => WebsiteData::Website, 'value' => $data->website],
			['name' => WebsiteData::Description, 'value' => $data->description],
		];

		// Insert records into the database.
		foreach ($settings as $rows) {
			$this->db->insert(SettingsEntity::Table, $rows)
				->execute();
		}

		// Save the installation step.
		$this->steps->setStep(4);
	}
}
