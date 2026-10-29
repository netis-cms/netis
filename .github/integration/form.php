<?php declare(strict_types=1);

/**
 * Forms of the pages the integration test gets.
 *
 * php form.php fill <page.html> <field> [name=value ...]
 *   The form with the field, filled as the browser would send it, with the given values instead: prints its action
 *   on the first line and the url-encoded fields on the second. A name given more times (roleId[]) sends every value,
 *   an empty value leaves the field out (an unchecked checkbox).
 *
 * php form.php value <page> <name>
 *   The value of a form field: "checked" or "" for a checkbox, the selected values joined by a comma for a select,
 *   the value otherwise. The page is HTML or the JSON payload of an AJAX request (its snippets).
 */

[, $command, $file, $name] = $argv + [null, null, null, null];
if (!in_array($command, ['fill', 'value'], true) || !is_string($file) || !is_string($name)) {
	fwrite(STDERR, "Usage: php form.php fill <page.html> <field> [name=value ...] | value <page> <name>\n");
	exit(2);
}

$html = (string) file_get_contents($file);
$payload = json_decode($html, true);
if (is_array($payload)) {
	$html = implode("\n", array_map('strval', $payload['snippets'] ?? []));
}

$dom = new DOMDocument;
@$dom->loadHTML('<?xml encoding="utf-8"?>' . $html); // @ HTML5 elements are unknown to libxml
$xpath = new DOMXPath($dom);


/** @return list<array{string, string}> the fields as the browser sends them: [name, value] */
function fields(DOMXPath $xpath, DOMNode $form): array
{
	$fields = [];
	$submitted = false;
	foreach ($xpath->query('.//input | .//select | .//textarea', $form) ?: [] as $element) {
		assert($element instanceof DOMElement);
		$name = $element->getAttribute('name');
		if ($name === '' || $element->hasAttribute('disabled')) {
			continue;
		}

		if ($element->tagName === 'select') {
			$options = iterator_to_array($xpath->query('.//option', $element) ?: []);
			$selected = array_filter($options, fn(DOMElement $option): bool => $option->hasAttribute('selected'));
			if ($selected === [] && !$element->hasAttribute('multiple') && $options !== []) {
				$selected = [$options[0]];
			}

			foreach ($selected as $option) {
				$fields[] = [$name, $option->getAttribute('value')];
			}

			continue;
		}

		if ($element->tagName === 'textarea') {
			$fields[] = [$name, $element->textContent];
			continue;
		}

		$type = strtolower($element->getAttribute('type') ?: 'text');
		if (in_array($type, ['checkbox', 'radio'], true)) {
			if ($element->hasAttribute('checked')) {
				$fields[] = [$name, $element->getAttribute('value') ?: 'on'];
			}
		} elseif ($type === 'submit') {
			// only the first submit button is pressed
			if (!$submitted) {
				$fields[] = [$name, $element->getAttribute('value')];
				$submitted = true;
			}
		} elseif ($type !== 'file' && $type !== 'button') {
			$fields[] = [$name, $element->getAttribute('value')];
		}
	}

	return $fields;
}


foreach ($xpath->query('//form') ?: [] as $form) {
	$fields = fields($xpath, $form);
	$hasField = $xpath->query('.//*[@name="' . $name . '" or @name="' . $name . '[]"]', $form)?->length > 0;
	if (!$hasField) {
		continue;
	}

	if ($command === 'value') {
		$element = $xpath->query('.//*[@name="' . $name . '"]', $form)?->item(0);
		assert($element instanceof DOMElement);
		$type = strtolower($element->getAttribute('type'));
		if ($type === 'checkbox') {
			echo $element->hasAttribute('checked') ? 'checked' : '';
		} else {
			echo implode(',', array_column(array_filter($fields, fn(array $field): bool => $field[0] === $name), 1));
		}

		exit(0);
	}

	$values = [];
	foreach (array_slice($argv, 4) as $argument) {
		[$key, $value] = explode('=', $argument, 2) + [1 => ''];
		$values[$key][] = $value;
	}

	$fields = array_values(array_filter($fields, fn(array $field): bool => !isset($values[$field[0]])));
	foreach ($values as $key => $list) {
		foreach ($list as $value) {
			if ($value !== '') {
				$fields[] = [$key, $value];
			}
		}
	}

	assert($form instanceof DOMElement);
	echo html_entity_decode($form->getAttribute('action')), "\n";
	echo implode('&', array_map(fn(array $field): string => rawurlencode($field[0]) . '=' . rawurlencode($field[1]), $fields));
	exit(0);
}

fwrite(STDERR, "No form with the field '$name' in $file.\n");
exit(1);
