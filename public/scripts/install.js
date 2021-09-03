/* sass */
import '../scss/install.scss';

/* js */
import bootstrap from 'bootstrap';
import naja from 'naja';
import {LiveForm, Nette} from 'live-form-validation';

/* live form validation */
LiveForm.setOptions({
	messageErrorClass: 'form-errors-live',
	messageErrorPrefix: '',
	wait: 500
});

Nette.initOnLoad();
window.Nette = Nette;
window.LiveForm = LiveForm;

/* naja initialize */
document.addEventListener('DOMContentLoaded', () => naja.initialize());

const submit = document.getElementById('btn-send');
if (submit) {
	submit.addEventListener('click', (e) => {
		submit.disabled = true;
		submit.innerText = null;
		submit.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
		naja.makeRequest('GET', e.target.getAttribute('data-url'), null, {history: false}).then();
	});
}

naja.uiHandler.addEventListener('interaction', e => {
	e.detail.options.element = e.detail.element;
});

naja.addEventListener('start', e => {
	const submit = document.querySelector('[data-btn-submit]')
	if(e.detail.options.element === submit) {
		submit.disabled = true;
		submit.innerText = null;
		submit.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
	}
});
