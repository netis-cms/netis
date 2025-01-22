export default class ErrorsExtension {
	initialize(naja) {
		const errorMessages = {
			403: 'You do not have the necessary permissions to perform this action.',
			404: 'Page not found.',
		};

		naja.addEventListener('error', (e) => {
			const error = e.detail.error;
			const errorMessage = errorMessages[error.response.status] || error.message;

			// Najdeme a vyprázdníme snippet
			const snippet = document.getElementById('snippet--message');
			snippet.innerHTML = '';

			// Vytvoření nových elementů
			const div = document.createElement('div');
			const button = document.createElement('button');

			div.className = 'alert alert-dismissible fade show border-0 rounded alert-danger';
			div.style.zIndex = '1030';
			div.textContent = errorMessage;
			snippet.append(div);

			button.className = 'btn-close';
			button.setAttribute('data-bs-dismiss', 'alert');
			div.append(button);
		});
	}
}
