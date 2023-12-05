<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use App\Modules\BaseFactory;
use Nette\Application\UI\Form;


class CommentsFactory
{
	public function __construct(
		private readonly BaseFactory $baseFactory,
	) {
	}


	public function create(): Form
	{
		$form = $this->baseFactory->create();
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form, CommentsData $data)
	{

	}
}
