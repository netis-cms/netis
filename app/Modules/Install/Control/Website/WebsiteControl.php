<?php

declare(strict_types=1);

namespace App\Modules\Install\Control\Website;

use App\Modules\Install\Steps;
use App\Services\Entity\SettingsEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Application\UI\Alert;
use Drago\Application\UI\ExtraControl;
use Drago\Application\UI\ExtraTemplate;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;


/**
 * WebsiteControl settings.
 * @property-read ExtraTemplate $template
 */
final class WebsiteControl extends ExtraControl
{
	public function __construct(
		private readonly Steps $steps,
		private readonly Connection $db,
	) {
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/Website.latte');
		$template->setTranslator($this->translator);
		$template->render();
	}


	public function createComponentWebsite(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText('website', 'Site name')
			->setRequired();

		$form->addText('description', 'Site description')
			->setRequired();

		$form->addSubmit('send', 'Save data');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Exception
	 */
	public function success(Form $form, ArrayHash $data): void
	{
		$settings = [
			['name' => 'website', 'value' => $data->website],
			['name' => 'description', 'value' => $data->description],
		];

		// Insert records into the database.
		foreach ($settings as $rows) {
			$this->db->insert(SettingsEntity::TABLE, $rows)
				->execute();
		}

		// Save the installation step.
		$this->steps->setStep(4);
		$this->getPresenter()->flashMessage(
			'Site settings successful.',
			Alert::SUCCESS,
		);
	}
}
