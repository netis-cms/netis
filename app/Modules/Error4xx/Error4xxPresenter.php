<?php

declare(strict_types=1);

namespace App\Modules\Error4xx;

use Nette;
use Nette\Application\BadRequestException;


/**
 * @property Error4xxTemplate $template
 */
final class Error4xxPresenter extends Nette\Application\UI\Presenter
{
	/**
	 * @throws BadRequestException
	 */
	protected function checkHttpMethod(): void
	{
		// allow access via all HTTP methods and ensure the request is a forward (internal redirect)
		if (!$this->getRequest()->isMethod(Nette\Application\Request::FORWARD)) {
			$this->error();
		}
	}


	public function renderDefault(Nette\Application\BadRequestException $exception): void
	{
		// load the template corresponding to the HTTP code
		$code = $exception->getCode();
		$file = is_file($file = __DIR__ . "/templates/Error/$code.latte")
			? $file
			: __DIR__ . '/templates/Error/4xx.latte';
		$this->template->httpCode = $code;
		$this->template->setFile($file);
	}
}
