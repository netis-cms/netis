/* Imports */
import naja from "naja";
import { LiveForm, Nette } from "live-form-validation"; // Ensure correct import for LiveForm
import SubmitButtonDisable from "./naja.button";
import SpinnerExtension from "./naja.spinner";
import ConfirmExtension from "./naja.confirm";
import ErrorsExtension from "./naja.errors";

window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* Initialize Nette (handles AJAX and form submission) */
Nette.initOnLoad();

/* Set options for LiveForm (error handling, form error styling, etc.) */
LiveForm.setOptions({
	messageErrorClass: 'errors-live',	// Class for error messages
	messageParentClass: 'form-error',	// Parent class for error messages
	messageErrorPrefix: '',			// Prefix for error messages
	wait: 500							// Wait time before form submission
});

/* Function to register all extensions */
function registerExtensions() {
	const extensions = [
		new SubmitButtonDisable(),		// Disables the submit button during requests
		new SpinnerExtension(),			// Shows a spinner during AJAX requests
		new ConfirmExtension(),			// Shows a confirmation dialog before executing actions
		new ErrorsExtension()			// Displays error messages when errors occur
	];

	// Register each extension with Naja
	extensions.forEach(extension => naja.registerExtension(extension));
}

/* Register the extensions to Naja */
registerExtensions();

/* Initialize Naja */
document.addEventListener('DOMContentLoaded', () => naja.initialize());
