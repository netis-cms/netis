export default class SpinnerExtension {
	initialize(naja) {
		const el = document.createElement('div');
		el.classList.add('spinner');
		naja.addEventListener('start', () => document.body.appendChild(el));
		naja.addEventListener('complete', () => document.body.appendChild(el).remove());
	}
}
