<?php

declare(strict_types=1);

namespace App\Core\Menu;


/**
 * @phpstan-type SidebarSubItemData array{
 *     title: string,
 *     link: string,
 *     allow: ?list<string>
 * }
 *
 * @phpstan-type SidebarItemData array{
 *     title: string,
 *     link: string,
 *     icon: ?string,
 *     allowAny: ?list<string>,
 *     items: list<SidebarSubItemData>
 * }
 */
class SidebarBuilder
{
	/** @var array<string, list<SidebarItemData>> */
	private array $structure = [];

	private string $currentSection = '';
	private int $currentKey = 0;


	/** Creates a new section (e.g., 'System'). */
	public function addSection(string $title): self
	{
		$this->currentSection = $title;

		if (!isset($this->structure[$title])) {
			$this->structure[$title] = [];
		}

		return $this;
	}


	/** Adds a main link to the current section. */
	public function addItem(string $title, string $link): self
	{
		$this->structure[$this->currentSection][] = [
			'title' => $title,
			'link' => $link,
			'icon' => null,
			'allowAny' => null,
			'items' => [],
		];

		$this->currentKey = array_key_last($this->structure[$this->currentSection]);

		return $this;
	}


	/** Adds an icon to the last added main item. */
	public function setIcon(string $icon): self
	{
		$this->structure[$this->currentSection][$this->currentKey]['icon'] = $icon;

		return $this;
	}


	/** Sets "isAnyAllowed" type permissions for the main item. */
	public function setAllowAny(string $resource, string ...$privileges): self
	{
		/** @var list<string> $allow */
		$allow = [$resource, ...$privileges];

		$this->structure[$this->currentSection][$this->currentKey]['allowAny'] = $allow;

		return $this;
	}


	/**
	 * Adds a sub-item (submenu) to the last added main item.
	 *
	 * @param ?list<string> $allow
	 */
	public function addSubItem(
		string $title,
		string $link,
		?array $allow = null,
	): self
	{
		$this->structure[$this->currentSection][$this->currentKey]['items'][] = [
			'title' => $title,
			'link' => $link,
			'allow' => $allow,
		];

		return $this;
	}


	/**
	 * Returns the generated array of DTO objects for the template.
	 *
	 * @return array<string, list<SidebarItem>>
	 */
	public function build(): array
	{
		/** @var array<string, list<SidebarItem>> $objectStructure */
		$objectStructure = [];

		foreach ($this->structure as $sectionTitle => $items) {
			$objectStructure[$sectionTitle] = [];

			foreach ($items as $item) {
				$subItems = [];

				foreach ($item['items'] as $subItem) {
					$subItems[] = new SidebarSubItem(
						$subItem['title'],
						$subItem['link'],
						$subItem['allow'],
					);
				}

				$objectStructure[$sectionTitle][] = new SidebarItem(
					$item['title'],
					$item['link'],
					$item['icon'],
					$item['allowAny'],
					$subItems,
				);
			}
		}

		return $objectStructure;
	}
}
