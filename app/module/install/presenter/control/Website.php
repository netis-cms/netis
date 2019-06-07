<?php


namespace Module\Install\Control;

use Dibi;
use Drago\Application\UI\Control;
use Drago\Application\UI\Factory;
use Drago\Http\Sessions;
use Drago\Localization\TranslateControl;
use Module\Install\Service\Query;
use Module\Install\Service\Steps;
use Nette\Application\UI\Form;


/**
 * Website settings.
 */
final class Website extends Control
{
	use Factory;
	use TranslateControl;

	/** @var Sessions */
	private $sessions;

	/** @var Steps */
	private $steps;

	/** @var Query */
	private $query;


	public function __construct(Sessions $sessions, Steps $steps, Query $query)
	{
		$this->sessions = $sessions;
		$this->steps = $steps;
		$this->query = $query;
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/../templates/Control.website.latte');
		$template->setTranslator($this->getTranslator());
		$template->form = $this['website'];
		$template->render();
	}


	public function createComponentWebsite(): Form
	{
		$form = $this->createForm();
		$form->setTranslator($this->getTranslator());

		$form->addText('website', 'form.name.web')
			->setRequired('form.required');

		$form->addText('description', 'form.description')
			->setRequired('form.required');

		$form->addSubmit('send', 'form.send.web');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	/**
	 * @throws Dibi\Exception
	 */
	public function success(Form $form): void
	{
		$values = $form->getValues();
		$table = $this->sessions->getSessionSection()->prefix . 'settings';
		$settings = [
			['name' => 'website', 'value' => $values->website],
			['name' => 'description', 'value' => $values->description],
		];

		// Insert records into the database.
		foreach ($settings as $rows) {
			$this->query->addRecord($table, $rows);
		}

		// Save the installation step.
		$this->steps->cache->save(Steps::STEP, ['step' => 4]);
		$this->presenter->flashMessage('message.web', 'success');
	}
}
