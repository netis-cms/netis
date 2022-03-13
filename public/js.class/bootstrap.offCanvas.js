/**
 * Disable button running an ajax request.
 */
class OffCanvas {
    initialize(Naja) {
        const offCanvas = (el) => {
            const element = document.getElementById(el);
            return new Bootstrap.Offcanvas(element);
        };
        Naja.addEventListener('success', e => {
            const payload = e.detail.payload;
            const items = [
                'permissions',
                'privileges',
                'resources',
                'roles',
                'access',
            ];
            items.forEach(function(item) {
                if (typeof payload[item] !== 'undefined') {
                    offCanvas(payload[item]).show();
                }
            });
        });
    }
}
Naja.registerExtension(new OffCanvas());
