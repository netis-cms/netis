export default class SubmitButtonDisable {
	initialize(naja) {
		let submitButton = null;

		// Funkce pro nastavení submitButton podle kliknutého tlačítka
		const setSubmitButton = (doc) => {
			const submit = doc.querySelector('[data-btn-submit]:not([disabled])'); // Jen aktivní tlačítka
			if (submit) {
				submitButton = submit;
			}
		};

		// Inicializujeme pro původní dokument a po každé aktualizaci snippetů
		setSubmitButton(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => setSubmitButton(e.detail.snippet));

		// Před odesláním deaktivujeme tlačítko
		naja.addEventListener('start', () => {
			if (submitButton) {
				submitButton.disabled = true;
			}
		});

		// Po dokončení povolíme tlačítko
		naja.addEventListener('complete', () => {
			if (submitButton) {
				submitButton.disabled = false;
			}
		});
	}
}
