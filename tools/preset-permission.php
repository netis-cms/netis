<?php

// Copies the administration components of project-blog and project-static-page into project-preset-permission with the
// "Permissions" steps of their README done (WriteAccess, $writeAllowed, setVisible() on grid actions, addButton).
//
//   php netis/tools/preset-permission.php
//
// Run it whenever one of the copied files changes in its package, so the preset never ships a stale fork.
// Every replacement must match exactly once, otherwise the script stops and writes nothing more: then the package
// file changed around a step and the step below has to be updated.
// The packages are expected in the parent directory of this repository (DRAGO_WORKSPACE overrides it).

declare(strict_types=1);

$root = getenv('DRAGO_WORKSPACE') ?: dirname(__DIR__, 2);
$edit = '$grid->addActionEdit(fn(int $id) => $this->handleEdit($id));';
$editV = "\$grid->addActionEdit(fn(int \$id) => \$this->handleEdit(\$id))\n\t\t\t->setVisible(\$this->isWriteAllowed());";
$del = '$grid->addActionDelete(fn(int $id) => $this->handleDelete($id));';
$delV = "\$grid->addActionDelete(fn(int \$id) => \$this->handleDelete(\$id))\n\t\t\t->setVisible(\$this->isWriteAllowed());";
$base = [
	"use Drago\\Datagrid\\DataGrid;\n" => "use Drago\\Datagrid\\DataGrid;\nuse Drago\\Permission\\WriteAccess;\n",
	"\tuse Component;\n" => "\tuse Component;\n\tuse WriteAccess;\n",
	"\t\t\$template->deleteTitle = \$this->deleteTitle;\n" => "\t\t\$template->deleteTitle = \$this->deleteTitle;\n\t\t\$template->writeAllowed = \$this->isWriteAllowed();\n",
];
$tpl = ["\tpublic ?string \$deleteTitle = null;\n" => "\tpublic ?string \$deleteTitle = null;\n\tpublic bool \$writeAllowed = false;\n"];
$grid = [$edit => $editV, $del => $delV];
$button = ['addButton: true}' => 'addButton: $writeAllowed}'];
$files = [
	'project-blog' => ['Backend/Blog/Component', [
		'BaseControl.php' => $base, 'BaseTemplate.php' => $tpl,
		'Article/ArticleControl.php' => $grid + [
			// the state of the comments in the grid switches them only with the write privilege
			"\t\treturn \$this->link('toggleComments!', ['id' => \$id]);" => "\t\treturn \$this->isWriteAllowed() ? \$this->link('toggleComments!', ['id' => \$id]) : null;",
			// the copy really returns null, so the ignore of the package version would be an unmatched error
			"\t * @phpstan-ignore return.unusedType\n" => '',
		],
		'Category/CategoryControl.php' => $grid,
		// the approve and delete buttons of the comments tab of an article
		'Article/Article.latte' => $button + ['<div class="article-comment-actions ' => '<div n:if="$writeAllowed" class="article-comment-actions '],
		'Category/Category.latte' => $button,
	]],
	'project-static-page' => ['Backend/Page/Component', [
		'BaseControl.php' => $base, 'BaseTemplate.php' => $tpl,
		'Page/PageControl.php' => $grid, 'Page/Page.latte' => $button,
	]],
];
foreach ($files as $package => [$dir, $list]) {
	foreach ($list as $file => $replacements) {
		$source = str_replace("\r\n", "\n", file_get_contents("$root/$package/resources/app/Presentation/$dir/$file"));
		foreach ($replacements as $from => $to) {
			if (substr_count($source, $from) !== 1) {
				fwrite(STDERR, "NOT ONCE in $package $file: $from\n");
				exit(1);
			}
			$source = str_replace($from, $to, $source);
		}
		$target = "$root/project-preset-permission/resources/app/Presentation/$dir/$file";
		@mkdir(dirname($target), 0o777, true);
		file_put_contents($target, $source);
		echo "written $dir/$file\n";
	}
}
