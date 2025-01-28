export default class SubmitButtonDisable {
	initialize(naja) {
		let submitButton = null;

		// Function to set the submitButton based on the clicked button
		const setSubmitButton = (doc) => {
			const submit = doc.querySelector('[data-btn-submit]:not([disabled])');
			if (submit) {
				submitButton = submit;
			}
		};

		// Initialize for the original document and after every snippet update
		setSubmitButton(document); // Initial check for submit button
		naja.snippetHandler.addEventListener('afterUpdate', (e) => setSubmitButton(e.detail.snippet));

		// Disable the button before submission
		naja.addEventListener('start', () => {
			if (submitButton) {
				submitButton.disabled = true;
			}
		});

		// Re-enable the button after the request completes
		naja.addEventListener('complete', () => {
			if (submitButton) {
				submitButton.disabled = false;
			}
		});
	}
}
