import naja from 'naja';
import {LiveForm, Nette} from 'live-form-validation';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new bootstrap.Alert(element)
});

// bootstrap tooltips
function tooltip () {
	const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	});
}

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
	const bootstrapTooltips = document.querySelectorAll('.tooltip');

	for (let bs of bootstrapTooltips) {
		bs.remove();
	}

	if (e.detail.options.element === submit) {
		submit.disabled = true;
		submit.innerText = null;
		submit.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
	}
});

const tooltipsExtension = {
	initialize(naja) {
		naja.addEventListener('init', () => {
			tooltip();
		});
		naja.addEventListener('complete', () => {
			tooltip();
		});
	}
}

naja.registerExtension(tooltipsExtension);
