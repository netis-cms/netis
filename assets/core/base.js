import naja from "naja";
import { LiveForm, Nette } from "live-form-validation";
import ErrorsHandler from "../naja/errors-handler.js";

/* Globals */
window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* Initialize Nette & Naja */
Nette.initOnLoad();
naja.initialize();

/* Configure LiveForm */
LiveForm.setOptions({
	showMessageClassOnParent: false,
	messageParentClass: false,
	controlErrorClass: '',
	controlValidClass: '',
	messageErrorClass: 'invalid-feedback fw-semibold',
	enableHiddenMessageClass: 'show-hidden-error',
	disableLiveValidationClass: 'no-live-validation',
	disableShowValidClass: 'no-show-valid',
	messageTag: 'span',
	messageIdPostfix: '_message',
	messageErrorPrefix: '',
	showAllErrors: false,
	showValid: false,
	wait: false
});

/* Register Naja extensions */
export function registerNajaExtensions(...extensions) {
	extensions.forEach(Extension => {
		naja.registerExtension(new Extension());
	});
}

registerNajaExtensions(
	ErrorsHandler
);
