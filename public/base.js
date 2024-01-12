import Bootstrap from "bootstrap/dist/js/bootstrap.bundle";
import naja from "naja";
import {LiveForm, Nette} from "live-form-validation";
import SubmitButtonDisable from "./naja.button";
import SpinnerExtension from "./naja.spinner";
import ConfirmExtension from "./naja.confirm";
import ErrorsExtension from "./naja.errors";
import EasyMde from "simplemde/dist/simplemde.min";

window.Bootstrap = Bootstrap;
window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* initialization nette */
Nette.initOnLoad();

const easyMde = new EasyMde({
	element: document.querySelector('.my-text-area'),
	placeholder: "Type here...",
	spellChecker: false,
	showIcons: ["code", "table"],
});

/* live form validation */
LiveForm.setOptions({
	messageErrorClass: 'errors-live',
	messageParentClass: 'form-error',
	messageErrorPrefix: '',
	wait: 500
});

/* submit button disable */
naja.registerExtension(
	new SubmitButtonDisable()
);

/* submit button disable */
naja.registerExtension(
	new SpinnerExtension()
);

/* confirm dialog */
naja.registerExtension(
	new ConfirmExtension()
);

/* ajax error message */
naja.registerExtension(
	new ErrorsExtension()
);
