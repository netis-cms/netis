import naja from "naja";
import TomSelect from "tom-select";

/**
 * Initializes TomSelect on elements with the class 'select-tom'.
 * If the element has the 'data-locked' attribute, the remove_button plugin is disabled.
 *
 * @param {HTMLElement} el The parent element where TomSelect should be initialized.
 */
function tomSelect(el) {
	el.querySelectorAll('.select-tom').forEach(e => {
		// Check if the element has the 'data-locked' attribute.
		const isLocked = e.hasAttribute('data-locked');

		// Initialize TomSelect with the remove_button plugin unless it's locked.
		e._tom = new TomSelect(e, {
			plugins: isLocked ? [] : ['remove_button'],
		});

		// Lock the TomSelect instance if it's marked as locked.
		if (isLocked) {
			e._tom.lock();
		}
	});
}

// Initialize TomSelect when the DOM content is loaded.
document.addEventListener('DOMContentLoaded', () => tomSelect(document));

// Reinitialize TomSelect after updating content with Naja.
naja.snippetHandler.addEventListener('afterUpdate', (e) => {
	tomSelect(e.detail.snippet);
});
