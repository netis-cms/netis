let reqCnt = 0;
export default class SpinnerExtension {
	initialize(Naja) {
		const el = document.createElement('div');
		el.classList.add('spinner');
		Naja.addEventListener('start', () => {
			reqCnt++;
			document.body.appendChild(el);
		});
		Naja.addEventListener('complete', () => {
			if (--reqCnt === 0) {
				document.body.appendChild(el).remove();
			}
		});
	}
}
