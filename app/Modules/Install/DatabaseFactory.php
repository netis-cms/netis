<?php

declare(strict_types=1);

namespace App\Modules\Install;

use dibi;
use Drago\Application\UI\ExtraTemplate;
use Drago\Localization\Translator;
use Drago\Parameters\Parameters;
use Drago\Utils\ExtraArrayHash;
use Nette\Application\UI\Form;
use Nette\DI\Config\Adapters\NeonAdapter;
use Throwable;


/**
 * Database server settings.
 */
final class DatabaseFactory
{
	public function __construct(
		private readonly Steps $steps,
		private readonly NeonAdapter $neonAdapter,
		private readonly Parameters $dirs,
		private readonly Translator $translator,
	) {
	}


	public function create(): Form
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
			}

		} catch (Throwable $t) {
			if ($t->getCode()) {
				$message = match ($t->getCode()) {
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
