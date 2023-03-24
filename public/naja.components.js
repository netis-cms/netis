import naja from "naja";

function initBsComponents(el) {
	for (let modal of el.querySelectorAll('.modal')) {
		if (!modal._bsModal) {
			modal._bsModal = new Bootstrap.Modal(modal, {
				keyboard: false
			});
		}
	}
	for (let offCanvas of el.querySelectorAll('.offcanvas')) {
		if (!offCanvas._bsOffcanvas) {
			offCanvas._bsOffcanvas = new Bootstrap.Offcanvas(offCanvas);
		}
	}
}

document.addEventListener('DOMContentLoaded', () => initBsComponents(document));
naja.snippetHandler.addEventListener('afterUpdate', (e) => {
	initBsComponents(e.detail.snippet);
});

naja.addEventListener('complete', (e) => {
	if (typeof e.detail.payload !== 'undefined') {
		let payload = e.detail.payload
		let doc = document;

		if (payload.modal) {
			let modal = doc.querySelector('#' + payload.modal);
			if (!modal.classList.contains('modal')) {
				modal = modal.querySelector('.modal');
			}

			if (modal?._bsModal) {
				modal._bsModal.show();
			}
		}

		if (payload.offcanvas) {
			let offCanvas = doc.querySelector('#' +  payload.offcanvas);
			if (!offCanvas.classList.contains('offcanvas')) {
				offCanvas = offCanvas.querySelector('.offcanvas');
			}

			if (offCanvas?._bsOffcanvas) {
				offCanvas._bsOffcanvas.show();
			}
		}

		if (payload.close){
			doc.querySelector('.offcanvas.show')?._bsOffcanvas?.hide();
			doc.querySelector('.modal.show')?._bsModal?.hide();
		}
	}
});
