import naja from "naja";
import { Modal, Offcanvas } from "bootstrap";

// Funkce pro inicializaci Bootstrap komponent
function initBsComponents(el) {
	// Funkce pro inicializaci jednotlivých komponent
	function initComponent(component, ComponentClass) {
		el.querySelectorAll(component).forEach((el) => {
			if (!el._bsInstance) {
				el._bsInstance = new ComponentClass(el, { keyboard: false });
			}
		});
	}

	// Inicializace modálů a offcanvas
	initComponent('.modal', Modal);
	initComponent('.offcanvas', Offcanvas);
}

document.addEventListener('DOMContentLoaded', () => initBsComponents(document));

// Po každé aktualizaci snippetů
naja.snippetHandler.addEventListener('afterUpdate', (e) => {
	initBsComponents(e.detail.snippet);
});

// Po dokončení požadavku (např. po úspěšném odeslání formuláře)
naja.addEventListener('complete', (e) => {
	if (e.detail.payload) {
		const doc = document;
		const { modal, offcanvas, close } = e.detail.payload;

		// Zobrazíme modal, pokud je uveden v payload
		if (modal) {
			const targetModal = doc.querySelector(`#${mo
