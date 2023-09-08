<?php

declare(strict_types=1);

namespace App\Modules\Install;

use App\Services\Entity\SettingsEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;


/**
 * WebsiteControl settings.
 */
final class WebsiteFactory
{
	public function __construct(
		private readonly Steps $steps,
		private readonly Connection $db,
		private readonly Translator $translator,
	) {
	}


	public function create(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText(WebsiteData::website, 'Site name')
			->setRequired();

		$form->addText(WebsiteData::description, 'Site description')
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
			['name' => WebsiteData::website, 'value' => $data->website],
			['name' => WebsiteData::description, 'value' => $data->description],
		];

		// Insert records into the database.
		foreach ($settings as $rows) {
			$this->db->insert(SettingsEntity::TABLE, $rows)
				->execute();
		}

		// Save the installation step.
		$this->steps->setStep(4);
	}
}
