export default class ErrorsExtension {
	/**
	 * Initializes the error handling extension for Naja requests.
	 *
	 * @param {Object} naja Naja instance for handling events.
	 */
	initialize(naja) {
		// Error messages corresponding to specific HTTP status codes.
		const errorMessages = {
			403: 'You do not have the necessary permissions to perform this action.',
			404: 'Page not found.',
		};

		// Listen for error events from Naja.
		naja.addEventListener('error', (e) => {
			const error = e.detail.error;
			// Retrieve the error message from the predefined error messages or fallback to the default message.
			const errorMessage = errorMessages[error.response.status] || error.message;

			// Find the element where the error message will be displayed and clear its current content.
			const snippet = document.getElementById('snippet--message');
			snippet.innerHTML = '';

			// Create new elements to display the error message.
			const div = document.createElement('div');
			const button = document.createElement('button');

			// Set classes and styles for the alert box.
			div.className = 'alert alert-dismissible fade show border-0 rounded alert-danger';
			div.style.zIndex = '1030';
			div.textContent = errorMessage; // Display the error message.
			snippet.append(div);

			// Create a button to close the alert.
			button.className = 'btn-close';
			button.setAttribute('data-bs-dismiss', 'alert');
			div.append(button);
		});
	}
}
