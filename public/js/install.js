/* sass */
import "../styles/install.scss";

/* js */
import "../base";

const button = document.getElementById('btn-send');
if (button) {
	button.addEventListener('click', (e) => {
		const url = e.target.getAttribute('data-url');
		button.disabled = true;
		naja.makeRequest('GET', url, null, {
			history: false,
		}).then();
	});
}
