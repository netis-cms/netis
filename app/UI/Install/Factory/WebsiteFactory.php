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
 * Factory for website settings during installation.
 * This factory handles the creation of the website settings form
 * and saves the submitted data to the database.
 */
final readonly class WebsiteFactory
{
	private Steps $steps;
	private Connection $db;
	private Factory $factory;


	public function __construct(
		Steps $steps,
		Connection $db,
		Factory $factory,
	) {
		$this->steps = $steps;
		$this->db = $db;
		$this->factory = $factory;
	}


	/**
	 * Creates the website settings form.
	 * This form allows users to input the site name and description.
	 */
	public function create(): Form
	{
		$form = $this->factory->create();

		// Add form fields for site name and description
		$form->addText(WebsiteData::Website, 'Site name')
			->setRequired();

		$form->addText(WebsiteData::Description, 'Site description')
			->setRequired();

		// Add submit button
		$form->addSubmit('send', 'Save data');

		// On success, call the success method
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}


	/**
	 * Handles the successful submission of the website settings form.
	 * Saves the submitted website name and description to the database.
	 * @throws Exception If database operation fails.
	 */
	public function success(Form $form, WebsiteData $data): void
	{
		// Prepare settings data for insertion into the database
		$settings = [
			['name' => WebsiteData::Website, 'value' => $data->website],
			['name' => WebsiteData::Description, 'value' => $data->description],
		];

		// Insert each setting into the database
		foreach ($settings as $rows) {
			$this->db->insert(SettingsEntity::Table, $rows)
				->execute();
		}

		// Update the installation step to step 4
		$this->steps->setStep(4);
	}
}
