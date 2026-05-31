export default class Spinner {
	initialize(naja) {
		let activeRequests = 0;
		const el = document.createElement('div');
		el.classList.add('spinner');
		document.body.appendChild(el);
		el.style.display = 'none';

		// Show the spinner when a request starts
		naja.addEventListener('start', () => {
			if (activeRequests === 0) {
				el.style.display = 'block';
			}
			activeRequests++;
		});

		// Hide the spinner when the last request completes
		naja.addEventListener('complete', () => {
			activeRequests--;
			if (activeRequests === 0) {
				el.style.display = 'none';
			}
		});
	}
}
