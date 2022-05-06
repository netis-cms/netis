<?php

declare(strict_types=1);

namespace App\Modules\Install\Presenters\Control\Website;

use App\Modules\Install\Services\Steps;
use App\Services\Entity\SettingsEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Application\UI\Alert;
use Drago\Localization\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;


/**
 * WebsiteControl settings.
 * @property-read WebsiteTemplate $template
 */
final class WebsiteControl extends Control
{
	public function __construct(
		private Translator $translator,
		private Steps $steps,
		private Connection $db,
	) {
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/Website.latte');
		$template->setTranslator($this->translator);
		$template->form = $this['website'];
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

		$form->addSubmit('send');
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
			'Site settings successful.', Alert::SUCCESS
		);
	}
}
