<?php


namespace Module\Install\Control;

use Dibi;
use Drago\Http\Sessions;
use Drago\Localization;
use Module\Install\Service;
use Nette\Application\UI;


/**
 * Website settings.
 */
final class Website extends UI\Control
{
	use Localization\TranslatorControl;

	/** @var Sessions */
	private $sessions;

	/** @var Service\Steps */
	private $steps;

	/** @var Service\Query */
	private $query;


	public function __construct(Sessions $sessions, Service\Steps $steps, Service\Query $query)
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


	public function createComponentWebsite(): UI\Form
	{
		$form = new UI\Form;
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
	public function success(UI\Form $form): void
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
		$this->steps->cache->save(Service\Steps::STEP, ['step' => 4]);
		$this->presenter->flashMessage('message.web', 'success');
	}
}
