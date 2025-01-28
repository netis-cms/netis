/* Imports */
import naja from "naja";
import { LiveForm, Nette } from "live-form-validation";
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
	messageErrorClass: 'errors-live',
	messageParentClass: 'form-error',
	messageErrorPrefix: '',
	wait: 500,
});

/* Function to register all extensions */
function registerExtensions() {
	const extensions = [
		new SubmitButtonDisable(),
		new SpinnerExtension(),
		new ConfirmExtension(),
		new ErrorsExtension(),
	];

	// Register each extension with Naja
	extensions.forEach(extension => naja.registerExtension(extension));
}

/* Register the extensions to Naja */
registerExtensions();

/* Initialize Naja */
document.addEventListener('DOMContentLoaded', () => naja.initialize());
