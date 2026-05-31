<?php

declare(strict_types=1);

namespace App\Core\Menu;


/** DTO representing a main menu item (1st level navigation). */
class SidebarItem
{
	/**
	 * @param SidebarSubItem[] $items
	 * @param list<string>|null $allowAny
	 */
	public function __construct(
		public string $title,
		public string $link,
		public ?string $icon = null,
		public ?array $allowAny = null,
		public array $items = [],
	) {
	}
}
