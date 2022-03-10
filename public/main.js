import naja from 'naja';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';

window.naja = naja;
window.bootstrap = bootstrap;
document.addEventListener('DOMContentLoaded', () => naja.initialize());

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new bootstrap.Alert(element)
});
