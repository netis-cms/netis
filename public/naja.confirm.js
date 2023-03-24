export default class ConfirmExtension {
	initialize(naja) {
		naja.uiHandler.addEventListener('interaction', (e) => {
			let el = e.detail.element;
			if (el.hasAttribute('data-confirm')
				&& ! window.confirm(el.getAttribute('data-confirm'))) {
				e.preventDefault();
			}
		});
	}
}
