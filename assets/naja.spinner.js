let reqCnt = 0;

export default class SpinnerExtension {
	initialize(naja) {
		const el = document.createElement('div');
		el.classList.add('spinner');
		document.body.appendChild(el); // Přidáme spinner do DOMu jen jednou

		// Skrýváme spinner, dokud není potřeba
		el.style.display = 'none';

		naja.addEventListener('start', () => {
			if (reqCnt === 0) {
				el.style.display = 'block'; // Zobrazíme spinner při prvním požadavku
			}
			reqCnt++;
		});

		naja.addEventListener('complete', () => {
			if (--reqCnt === 0) {
				el.style.display = 'none'; // Skryjeme spinner po dokončení posledního požadavku
			}
		});
	}
}
