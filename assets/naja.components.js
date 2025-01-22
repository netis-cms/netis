import naja from "naja";
import { Modal, Offcanvas } from "bootstrap";

// Function to initialize Bootstrap components (Modal and Offcanvas)
function initBsComponents(el) {
	// Initialize Modals
	el.querySelectorAll('.modal').forEach((modal) => {
		if (!modal._bsModal) {
			modal._bsModal = new Modal(modal, { keyboard: false });
		}
	});

	// Initialize Offcanvas components
	el.querySelectorAll('.offcanvas').forEach((offCanvas) => {
		if (!offCanvas._bsOffcanvas) {
			offCanvas._bsOffcanvas = new Offcanvas(offCanvas);
		}
	});
}

document.addEventListener('DOMContentLoaded', () => initBsComponents(document));

naja.snippetHandler.addEventListener('afterUpdate', (e) => {
	initBsComponents(e.detail.snippet);
});

naja.addEventListener('complete', (e) => {
	const { payload } = e.detail;

	if (payload) {
		const doc = document;

		// Show modal if specified in payload
		if (payload.modal) {
			let modal = doc.querySelector(`#${payload.modal}`) || doc.querySelector(`#${payload.modal} .modal`);
			modal?._bsModal?.show();
		}

		// Show offcanvas if specified in payload
		if (payload.offcanvas) {
			let offCanvas = doc.querySelector(`#${payload.offcanvas}`) || doc.querySelector(`#${payload.offcanvas} .offcanvas`);
			offCanvas?._bsOffcanvas?.show();
		}

		// Close any open modal or offcanvas
		if (payload.close) {
			doc.querySelector('.offcanvas.show')?._bsOffcanvas?.hide();
			doc.querySelector('.modal.show')?._bsModal?.hide();
		}
	}
});
