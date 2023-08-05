<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use Nette\Application\UI\Form;


class CommentsFactory
{
	public function create(): Form
	{
		$form = new Form();
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = [$this, 'success'];
		return $form;
	}


	public function success(Form $form, CommentsData $data)
	{

	}
}
