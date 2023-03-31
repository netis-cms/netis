import naja from "naja";
import TomSelect from "tom-select";

function tomSelect(el) {
	for(let e of el.querySelectorAll('.select-tom')) {
		let isLocked = e.hasAttribute('data-locked');
		e._tom = new TomSelect(e, {
			plugins: isLocked? []:['remove_button'],
		});
		if (isLocked) {
			e._tom.lock();
		}
	}
}

document.addEventListener('DOMContentLoaded', () => tomSelect(document));
naja.snippetHandler.addEventListener('afterUpdate', (e) => {
	tomSelect(e.detail.snippet);
});
