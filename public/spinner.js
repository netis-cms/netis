export default class SpinnerExtension {
	constructor(selector) {
		this.selector = selector;
	}
	initialize(naja) {
		const el = document.querySelector(this.selector);
		naja.addEventListener('start', () => el.classList.add('spinner'));
		naja.addEventListener('complete', () => el.classList.remove('spinner'));
	}
}
