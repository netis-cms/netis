let reqCnt = 0;
export default class SpinnerExtension {
	initialize(naja) {
		const el = document.createElement('div');
		el.classList.add('spinner');
		naja.addEventListener('start', () => {
			reqCnt++;
			document.body.appendChild(el);
		});
		naja.addEventListener('complete', () => {
			if (--reqCnt === 0) {
				document.body.appendChild(el).remove();
			}
		});
	}
}
