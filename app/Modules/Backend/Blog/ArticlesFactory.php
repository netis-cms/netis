<?php

declare(strict_types=1);

namespace App\Modules\Backend\Blog;

use App\Modules\Backend\Sign\User;
use App\Modules\BaseFactory;
use DateTimeImmutable;
use Nette\Application\UI\Form;
use Throwable;
use Tracy\Debugger;


class ArticlesFactory
{
	public function __construct(
		//private readonly ArticlesRepository $articleRepository,
		private readonly User $user,
		private readonly BaseFactory $baseFactory,
	) {
	}


	public function create(): Form
	{
		$form = $this->baseFactory->create();
		$form->addText(ArticlesEntity::ColumnTitle, 'Title')
			->setRequired();

		$form->addTextArea(ArticlesEntity::ColumnContent, 'Content')
			->setRequired();

		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}


	public function success(Form $form, ArticlesData $data): void
	{
		try {
			$data->category_id = 1;
			$data->created_at = new DateTimeImmutable();
			$data->author_id = $this->user->getId();
			//$this->articleRepository->put($data->toArray());
			Debugger::barDump($data);

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
