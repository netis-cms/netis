<?php
declare(strict_types=1);

namespace Module\Install\Control;

use App\Entity\SettingsEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Localization\Translator;
use Module\Install\Service\Steps;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\ArrayHash;


/**
 * WebsiteControl settings.
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
		/** @var Template $template */
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.website.latte');
		$template->setTranslator($this->translator);
		$template->form = $this['website'];
		$template->render();
	}


	public function createComponentWebsite(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText('website', 'form.name.web')
			->setRequired('form.required');

		$form->addText('description', 'form.description')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.web');
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
			$this->db->insert(SettingsEntity::TABLE, $rows)->execute();
		}

		// Save the installation step.
		$this->steps->cache->save(Steps::STEP, ['step' => 4]);
		$this->presenter->flashMessage('message.web', 'success');
	}
}
