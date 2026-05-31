<?php

declare(strict_types=1);

namespace App\Install\Factory;

use App\Core\Settings\SettingsEntity;
use App\Install\Steps;
use Dibi\Connection;
use Dibi\Exception;
use Nette\Application\UI\Form;


/** Factory for website settings during installation. */
final readonly class WebsiteFactory
{
	private Steps $steps;
	private Connection $db;


	public function __construct(
		Steps $steps,
		Connection $db,
	) {
		$this->steps = $steps;
		$this->db = $db;
	}


	/** Creates the website settings form. */
	public function create(): Form
	{
		$form = new Form;
		$form->addText(WebsiteValues::Website, 'Site name')
			->setHtmlAttribute('autocomplete', 'off')
			->setRequired();

		$form->addText(WebsiteValues::Description, 'Site description')
			->setHtmlAttribute('autocomplete', 'off')
			->setRequired();

		$form->addSubmit('send', 'Save data');
		$form->onSuccess[] = $this->success(...);

		return $form;
	}


	/** Handles the successful submission of the website settings form.
	 * @throws Exception
	 */
	public function success(Form $form, WebsiteValues $data): void
	{
		$settings = [
			['name' => WebsiteValues::Website, 'value' => $data->website],
			['name' => WebsiteValues::Description, 'value' => $data->description],
		];

		foreach ($settings as $rows) {
			$this->db->insert(SettingsEntity::Table, $rows)
				->execute();
		}

		$this->steps->setStep(4);
	}
}
