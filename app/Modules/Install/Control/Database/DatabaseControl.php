<?php

declare(strict_types=1);

namespace App\Modules\Install\Control\Database;

use App\Modules\Install\Steps;
use dibi;
use Drago\Application\UI\Alert;
use Drago\Application\UI\ExtraControl;
use Drago\Application\UI\ExtraTemplate;
use Drago\Parameters\Parameters;
use Drago\Utils\ExtraArrayHash;
use Nette\Application\UI\Form;
use Nette\DI\Config\Adapters\NeonAdapter;
use Throwable;


/**
 * Database server settings.
 * @property-read ExtraTemplate $template
 */
final class DatabaseControl extends ExtraControl
{
	public function __construct(
		private readonly Steps $steps,
		private readonly NeonAdapter $neonAdapter,
		private readonly Parameters $dirs,
	) {
	}


	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/Database.latte');
		$template->setTranslator($this->translator);
		$template->render();
	}


	protected function createComponentDatabase(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);

		$form->addText('host', 'Database server')
			->setRequired();

		$form->addText('user', 'Username')
			->setRequired();

		$form->addText('password', 'Password')
			->setRequired();

		$form->addText('database', 'Database name')
			->setRequired();

		$form->addSubmit('send', 'Connection test');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form, ExtraArrayHash $data): void
	{
		try {

			// Check database connection.
			if (dibi::connect($data->toArray())->isConnected()) {

				// Parameters for generate config neon file.
				$arr = ['extensions' => [
					'dibi' => 'Dibi\Bridges\Nette\DibiExtension22',
				],
					'dibi' => [
						'host' => $data->host,
						'username' => $data->user,
						'password' => $data->password,
						'database' => $data->database,
						'driver' => 'mysqli',
						'lazy' => true,
					],
				];

				// Generate and save the configuration file.
				$content = $this->neonAdapter->dump($arr);
				$file = fopen($this->dirs->getAppDir() . '/Services/db.neon', 'w');
				fwrite($file, $content);

				// Save the installation step.
				$this->steps->setStep(2);
				$this->getPresenter()->flashMessage(
					'Database settings were successful.',
					Alert::SUCCESS,
				);
			}

		} catch (Throwable $e) {
			if ($e->getCode()) {
				$message = match ($e->getCode()) {
					1044 => 'Access denied, check database settings.',
					1045 => 'Failed to verify database username or password.',
					1049 => 'The database name does not exist.',
					2002 => 'The database server did not respond.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
		}
	}
}
