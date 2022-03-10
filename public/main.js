import naja from 'naja';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';

window.naja = naja;
window.bootstrap = bootstrap;
document.addEventListener('DOMContentLoaded', () => naja.initialize());

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new bootstrap.Alert(element)
});

naja.uiHandler.addEventListener('interaction', e => {
	e.detail.options.element = e.detail.element;
});

// button disable
const submit = document.getElementById('btn-send');
if (submit) {
	submit.addEventListener('click', (e) => {
		const url = e.target.getAttribute('data-url');
		submit.disabled = true;
		naja.makeRequest('GET', url, null, {
			history: false,
		}).then();
	});
}

// submit button disable
naja.addEventListener('start', e => {
	const submit = document.querySelector('[data-btn-submit]')
	if (e.detail.options.element === submit) {
		submit.disabled = true;
	}
});
