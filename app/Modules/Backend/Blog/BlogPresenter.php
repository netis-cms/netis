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
		private readonly ArticleComponent $articleComponent,
	) {
		parent::__construct();
	}


	protected function createComponentArticles(): Form
	{
		$articles = $this->articleComponent;
		$articles->translator = $this->getTranslator();

		$form = $articles->factory();
		$form->onSuccess[] = function () {
			$this->flashMessage('send');
			$this->redirect('this');
		};

		return $form;
	}
}
