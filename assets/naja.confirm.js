export default class ConfirmExtension {
	initialize(naja) {
		naja.uiHandler.addEventListener('interaction', (e) => {
			const el = e.detail.element;
			const confirmMessage = el.getAttribute('data-confirm') || el.getAttribute('data-datagrid-confirm');
			
			// Pokud má element nějaký confirm atribut a uživatel nepotvrdí
			if (confirmMessage && !window.confirm(confirmMessage)) {
				e.preventDefault();
			}
		});
	}
}
