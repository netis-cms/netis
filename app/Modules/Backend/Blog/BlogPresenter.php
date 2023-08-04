<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use App\Modules\Backend\BackendPresenter;
use Nette\Application\UI\Form;


/**
 * @property-read BlogTemplate $template
 */
final class BlogPresenter extends BackendPresenter
{
	public function __construct(
		private readonly ArticleFactory $articleFactory,
	) {
		parent::__construct();
	}


	protected function createComponentArticles(): Form
	{
		$form = $this->articleFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('send');
			$this->redirect('this');
		};
		return $form;
	}
}
