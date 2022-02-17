import naja from "naja";

window.naja = naja;
document.addEventListener('DOMContentLoaded', () => naja.initialize());

const submit = document.getElementById('btn-send');
const submitSpinner = function (element) {
	element.disabled = true;
	element.innerText = 'Wait... ';
	element.innerHTML += '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
}

if (submit) {
	submit.addEventListener('click', (e) => {
		const url = e.target.getAttribute('data-url');
		submitSpinner(submit);
		naja.makeRequest('GET', url, null, {
			history: false,
		}).then();
	});
}

naja.uiHandler.addEventListener('interaction', e => {
	e.detail.options.element = e.detail.element;
});

naja.addEventListener('start', e => {
	const submit = document.querySelector('[data-btn-submit]')
	if (e.detail.options.element === submit) {
		submitSpinner(submit);
	}
});
