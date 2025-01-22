/* Sass */
import "../styles/install.scss";

/* JS */
import "../base";
import { Alert } from "bootstrap";

/* Inicializace Naja */
document.addEventListener('DOMContentLoaded', () => naja.initialize());

/* Inicializace Bootstrap alert komponent */
document.querySelectorAll('.alert').forEach((element) => {
	new Alert(element);
});

/* Odesílání GET požadavku při kliknutí na tlačítko */
const button = document.getElementById('btn-send');
if (button) {
	button.addEventListener('click', (e) => {
		const url = e.target.dataset.url; // Použijeme dataset pro lepší čitelnost
		button.disabled = true;

		// Odeslání GET požadavku pomocí Naja
		naja.makeRequest('GET', url, null, { history: false }).then(() => {
			// Pokud je třeba nějaké další zpracování po dokončení požadavku, můžeme to přidat zde.
		}).catch(() => {
			// Volitelné: ošetření chyb, pokud je potřeba.
			button.disabled = false; // Znovu povolíme tlačítko v případě chyby
		});
	});
}
