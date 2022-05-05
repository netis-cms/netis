export default class OffCanvas {

    /** @param items {[]} */
    constructor(items) {
        this.items = items;
    }

    initialize(naja) {
        const doc = document;
        const offCanvas = doc.querySelectorAll('.offcanvas');
        const elementId = (id) => doc.getElementById(id);
        for (let c of offCanvas) c.bo = new Bootstrap.Offcanvas(c);
        naja.addEventListener('complete', (e) => {
            this.items.forEach(function(item) {
                const payload = e.detail.payload;
                if (typeof payload[item] !== 'undefined') {
                    let offCanvasElement = elementId(payload[item]);
                    if (offCanvasElement) offCanvasElement.bo.show();

                } else {
                    if (payload['close'] === 'close') {
                        let offCanvasElement = elementId(item);
                        if (offCanvasElement) offCanvasElement.bo.hide();
                    }
                }
            });
        });
    }
}
