import jQuery from 'jquery';
import {LiveForm, Nette} from 'live-form-validation';

window.$ = window.jQuery = jQuery;
window.Nette = Nette;
window.LiveForm = LiveForm;

Nette.initOnLoad();

/* live form validation */
LiveForm.setOptions({
	messageErrorClass: 'form-errors-live',
	messageErrorPrefix: '',
	wait: 500
});
