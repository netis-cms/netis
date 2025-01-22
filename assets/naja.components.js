import naja from "naja";
import { Modal, Offcanvas } from "bootstrap";

// Function to initialize Bootstrap components (modals and offcanvases)
function initBsComponents(el) {
	// Helper function to initialize a component
	const initComponent = (selector, ComponentClass) => {
		el.querySelectorAll(selector).forEach((element) => {
			if (!element._bsInstance) {
				element._bsInstance = new ComponentClass(element, { keyboard: false });
			}
		});
	};

	// Initialize modals and offcanvases
	initComponent('.modal', Modal);
	initComponent('.offcanvas', Offcanvas);
}

// Initialize Bootstrap components when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => initBsComponents(document));

// After snippet updates, reinitialize Bootstrap components
naja.snippetHandler.addEventListener('afterUpdate', (e) => {
	initBsComponents(e.detail.snippet);
});

// Helper function to handle showing of modals or offcanvases
function handleComponent(payload, doc) {
	// Show modal if specified
	if (payload.modal) {
		let modal = doc.querySelector(`#${payload.modal}`);
		if (!modal.classList.contains('modal')) {
			modal = modal.querySelector('.modal');
		}
		modal?._bsModal?.show();
	}

	// Show offcanvas if specified
	if (payload.offcanvas) {
		let offCanvas = doc.querySelector(`#${payload.offcanvas}`);
		if (!offCanvas.classList.contains('offcanvas')) {
			offCanvas = offCanvas.querySelector('.offcanvas');
		}
		offCanvas?._bsOffcanvas?.show();
	}

	// Close any open modal or offcanvas
	if (payload.close) {
		doc.querySelector('.offcanvas.show')?._bsOffcanvas?.hide();
		doc.querySelector('.modal.show')?._bsModal?.hide();
	}
}

// Handle components after a request completes
naja.addEventListener('complete', (e) => {
	if (e.detail.payload) {
		handleComponent(e.detail.payload, document);
	}
});
