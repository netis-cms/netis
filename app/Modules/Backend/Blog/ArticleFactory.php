<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use App\Modules\Backend\Sign\User;
use DateTimeImmutable;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Throwable;


class ArticleFactory
{
	use SmartObject;

	public function __construct(
		private readonly ArticleRepository $articleRepository,
		public readonly User $user,
		public readonly Translator $translator,
	) {
	}


	public function create(): Form
	{
		$form = new Form();
		$form->setTranslator($this->translator);

		$form->addText(ArticleEntity::TITLE, 'Title')
			->setRequired();

		$form->addTextArea(ArticleEntity::CONTENT, 'Content')
			->setRequired();

		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}


	public function success(Form $form, ArticleEntity $data): void
	{
		try {
			$data->category_id = 1;
			$data->created_at = new DateTimeImmutable();
			$data->author_id = $this->user->getId();
			$this->articleRepository->put($data->toArray());

		} catch (Throwable $t) {
			if ($t->getCode()) {
				$message = match ($t->getCode()) {
					1062 => 'Duplicate entry.',
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
		}
	}
}
