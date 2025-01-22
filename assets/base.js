import naja from "naja";
import { LiveForm, Nette } from "live-form-validation";
import SubmitButtonDisable from "./naja.button";
import SpinnerExtension from "./naja.spinner";
import ConfirmExtension from "./naja.confirm";
import ErrorsExtension from "./naja.errors";

// Inicializace Nette
Nette.initOnLoad();

// Nastavení pro LiveForm
LiveForm.setOptions({
	messageErrorClass: 'errors-live',
	messageParentClass: 'form-error',
	messageErrorPrefix: '',
	wait: 500
});

// Funkce pro registraci všech rozšíření
function registerExtensions() {
	const extensions = [
		new SubmitButtonDisable(),
		new SpinnerExtension(),
		new ConfirmExtension(),
		new ErrorsExtension()
	];

	extensions.forEach(extension => naja.registerExtension(extension));
}

// Registrace rozšíření
registerExtensions();
