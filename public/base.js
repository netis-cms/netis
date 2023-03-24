import Bootstrap from "bootstrap/dist/js/bootstrap.bundle";
import jQuery from "jquery"
import naja from "naja";
import {LiveForm, Nette} from "live-form-validation";
import SubmitButtonDisable from "./naja.button";
import SpinnerExtension from "./naja.spinner";

window.jQuery = window.$ = jQuery;
window.Bootstrap = Bootstrap;
window.LiveForm = LiveForm;
window.Nette = Nette;
window.naja = naja;

/* initialization naja */
document.addEventListener('DOMContentLoaded',
	naja.initialize.bind(naja)
);

/* initialization nette */
Nette.initOnLoad();

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
