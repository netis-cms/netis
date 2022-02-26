import naja from "naja";
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';

window.naja = naja;
document.addEventListener('DOMContentLoaded', () => naja.initialize());

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

const submit = document.getElementById('btn-send');
const submitSpinner = function (element) {
	element.disabled = true;
	element.innerText = 'Wait... ';
	element.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
}

naja.uiHandler.addEventListener('interaction', e => {
	e.detail.options.element = e.detail.element;
});

// button click spinner
if (submit) {
	submit.addEventListener('click', (e) => {
		const url = e.target.getAttribute('data-url');
		submitSpinner(submit);
		naja.makeRequest('GET', url, null, {
			history: false,
		}).then();
	});
}

// button submit spinner
naja.addEventListener('start', e => {
	const submit = document.querySelector('[data-btn-submit]')
	const bootstrapTooltips = document.querySelectorAll('.tooltip');
	for (let bs of bootstrapTooltips) {
		bs.remove();
	}
	if (e.detail.options.element === submit) {
		submitSpinner(submit);
	}
});

// bootstrap tooltip
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
