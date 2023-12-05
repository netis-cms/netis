<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use App\Modules\Backend\BackendPresenter;
use Nette\Application\UI\Form;


/**
 * @property BlogTemplate $template
 */
final class BlogPresenter extends BackendPresenter
{
	public function __construct(
		private readonly ArticlesFactory $articlesFactory,
		private readonly CommentsFactory $commentsFactory,
	) {
		parent::__construct();
	}


	protected function createComponentArticles(): Form
	{
		$form = $this->articlesFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('send');
			//$this->redirect('this');
		};
		return $form;
	}


	protected function createComponentComments(): Form
	{
		$form = $this->commentsFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('send');
			$this->redirect('this');
		};
		return $form;
	}
}
