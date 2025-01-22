/* Sass */
import "../styles/install.scss";

/* JS */
import "../base";
import { Alert } from "bootstrap";

/* Initialize Bootstrap alert components */
document.querySelectorAll('.alert').forEach((element) => {
	new Alert(element);
});

/* Sending a GET request when the button is clicked */
const button = document.getElementById('btn-send');
if (button) {
	button.addEventListener('click', (e) => {
		const url = e.target.dataset.url; // Use dataset for better readability
		button.disabled = true;

		// Send GET request using Naja
		naja.makeRequest('GET', url, null, { history: false }).then(() => {
			// If additional processing is needed after the request, we can add it here.
		}).catch(() => {
			// Optional: handle errors if needed.
			button.disabled = false; // Re-enable the button in case of error
		});
	});
}
