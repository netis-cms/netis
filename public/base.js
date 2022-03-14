import * as Bootstrap from 'bootstrap/dist/js/bootstrap.bundle';
import {LiveForm, Nette} from 'live-form-validation';
import Naja from 'naja';

window.Bootstrap = Bootstrap;
window.LiveForm = LiveForm;
window.Nette = Nette;
window.Naja = Naja;

/* initialization naja */
document.addEventListener('DOMContentLoaded',
	Naja.initialize.bind(Naja)
);

/* initialization nette */
Nette.initOnLoad();

/* live form validation */
LiveForm.setOptions({
	messageErrorClass: 'errors-live',
	messageErrorPrefix: '',
	wait: 500
});
