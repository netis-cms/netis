export default class ErrorsHandler {
	/**
	 * Initializes the error handling extension for Naja requests.
	 * @param {Object} naja Naja instance for handling events.
	 */
	initialize(naja) {
		// Error messages corresponding to specific HTTP status codes.
		const errorMessages = {
			403: 'You do not have the necessary permissions to perform this action.',
			404: 'Page not found.',
			500: 'Internal server error, please try again later.',
			401: 'You are not authorized to perform this action.',
			// Add more error codes as needed
		};

		// Listen for error events from Naja.
		naja.addEventListener('error', (e) => {
			const error = e.detail.error;

			// Ensure the error response has a status
			if (!error.response || !error.response.status) {
				console.error('Error: Invalid response or missing status.');
				return;
			}

			// Retrieve the error message from the predefined error messages or fallback to the default message.
			const errorMessage = errorMessages[error.response.status] || 'An unexpected error occurred.';

			// Find the element where the error message will be displayed and clear its current content.
			const snippet = document.getElementById('snippet--message');
			if (snippet) {
				snippet.innerHTML = '';
			} else {
				console.error('Error: Snippet element with ID "snippet--message" not found.');
				return;
			}

			// Create new elements to display the error message.
			const div = document.createElement('div');
			const button = document.createElement('button');

			// Set classes and styles for the alert box.
			div.className = 'alert alert-dismissible fade show border-0 rounded alert-danger';
			div.style.zIndex = '1030';
			div.textContent = errorMessage;
			snippet.append(div);

			// Create a button to close the alert.
			button.className = 'btn-close';
			button.setAttribute('data-bs-dismiss', 'alert');
			div.append(button);
		});
	}
}
