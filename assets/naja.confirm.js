export default class ConfirmExtension {
	/**
	 * Initializes the confirmation extension for Naja UI interactions.
	 * Listens for user interactions and shows a confirmation dialog when needed.
	 *
	 * @param {Object} naja Naja instance to handle UI interactions.
	 */
	initialize(naja) {
		// Listen for 'interaction' events triggered by UI actions.
		naja.uiHandler.addEventListener('interaction', (e) => {
			const el = e.detail.element;
			// Retrieve the confirmation message from either 'data-confirm' or 'data-datagrid-confirm' attributes.
			const confirmMessage = el.getAttribute('data-confirm') || el.getAttribute('data-datagrid-confirm');

			// If the element has a confirmation attribute and the user does not confirm, prevent the default action.
			if (confirmMessage && !window.confirm(confirmMessage)) {
				e.preventDefault();
			}
		});
	}
}
