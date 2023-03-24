export default class ErrorsExtension {
	initialize(naja) {
		naja.addEventListener('error', (e) => {
			let errorMessage = e.detail.error.message;
			switch (e.detail.error.response.status) {
				case 403: errorMessage = 'You do not have the necessary permissions to perform this action.'; break;
				case 404: errorMessage = 'Page not found.'; break;
			}
			let snippet = document.getElementById('snippet--message');
			let div = document.createElement('div');
			let button = document.createElement('button');
			for (let child of snippet.children) {
				snippet.removeChild(child);
			}

			div.className = 'alert alert-dismissible fade show border-0 rounded alert-danger';
			div.style.zIndex = '1030';
			div.textContent = errorMessage;
			snippet.append(div);

			button.className = 'btn-close'
			button.setAttribute('data-bs-dismiss', 'alert');
			div.append(button);
		});
	}
}
