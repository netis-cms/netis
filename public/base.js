import Bootstrap from 'bootstrap/dist/js/bootstrap.bundle';
import {LiveForm, Nette} from 'live-form-validation';
import Naja from 'naja';
import SubmitButtonDisable from './button';
import SpinnerExtension from './spinner';

window.Bootstrap = Bootstrap;
window.LiveForm = LiveForm;
window.Nette = Nette;
window.Naja = Naja;

/* initialization naja */
document.addEventListener('DOMContentLoaded',
	Naja.initialize.bind(Naja)
);

/* initialization nette */
Nette.initOnLoad();

/* live form validation */
LiveForm.setOptions({
	messageErrorClass: 'errors-live',
	messageErrorPrefix: '',
	wait: 500
});

/* submit button disable */
Naja.registerExtension(
	new SubmitButtonDisable()
);

/* submit button disable */
Naja.registerExtension(
	new SpinnerExtension()
);

/* standard button disable */
const button = document.getElementById('btn-send');
if (button) {
	button.addEventListener('click', (e) => {
		const url = e.target.getAttribute('data-url');
		button.disabled = true;
		Naja.makeRequest('GET', url, null, {
			history: false,
		}).then();
	});
}
