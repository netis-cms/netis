/* js */
import naja from 'naja';
import {LiveForm, Nette} from 'live-form-validation';
import bootstrap from 'bootstrap';

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new bootstrap.Alert(element)
});

/* live form validation */
LiveForm.setOptions({
	messageErrorClass: 'form-errors-live',
	messageErrorPrefix: '',
	wait: 500
});

Nette.initOnLoad();
window.Nette = Nette;
window.LiveForm = LiveForm;
window.naja = naja;

/* naja initialize */
document.addEventListener('DOMContentLoaded', () => naja.initialize());

/* button spinner */
const submit = document.getElementById('btn-send');
if (submit) {
	submit.addEventListener('click', (e) => {
		submit.disabled = true;
		submit.innerText = null;
		submit.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
		naja.makeRequest('GET', e.target.getAttribute('data-url'), null, {history: false}).then();
	});
}

/* form button spinner */
naja.uiHandler.addEventListener('interaction', e => {
	e.detail.options.element = e.detail.element;
});

naja.addEventListener('start', e => {
	const submit = document.querySelector('[data-btn-submit]')
	if (e.detail.options.element === submit) {
		submit.disabled = true;
		submit.innerText = null;
		submit.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
	}
});
