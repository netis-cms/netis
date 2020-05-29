<?php

declare(strict_types = 1);

namespace Module\Admin;

use Base\Backend;
use Drago\Localization\TranslatorAdapter;
use Drago\User\Gravatar;


final class AdminPresenter extends Backend
{
	use TranslatorAdapter;

	/**
	 * @var Gravatar
	 * @inject
	 */
	public $gravatar;


	protected function beforeRender(): void
	{
		parent::beforeRender();
		$gravatar = $this->gravatar;
		$gravatar->setEmail($this->user->identity->data['email'] ?? 'someone@somewhere.com');
		$gravatar->setSize(80);

		$this->template->gravatar = $this->gravatar->getGravatar();
	}
}
