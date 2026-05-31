export default class HyperlinkDisable {
	initialize(naja) {
		const hyperlinkDisable = (doc) => {
			const links = doc.querySelectorAll('[data-link-disable]');
			links.forEach((link) => {
				link.addEventListener('click', (event) => {
					link.classList.add('disabled');
				});
			});
			return links;
		};

		const initialLinks = hyperlinkDisable(document);
		naja.snippetHandler.addEventListener('afterUpdate', (e) => {
			hyperlinkDisable(e.detail.snippet);
		});

		naja.addEventListener('complete', () => {
			initialLinks.forEach((link) => {
				link.classList.remove('disabled');
			});
		});
	}
}
