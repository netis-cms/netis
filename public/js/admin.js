/* sass */
import '../styles/admin.scss';

/* js */
import '../base';
import '../main';
import Nanobar from 'nanobar';
import PerfectScrollbar from 'perfect-scrollbar';
import 'sidebar-menu-compostrap';
import 'sidebar-skeleton-compostrap';

/* scripts */
document.addEventListener('DOMContentLoaded', () => {
	new Nanobar().go(100);
	new PerfectScrollbar('.scrollbar', {
		wheelSpeed: 0.3
	});
});

/* bootstrap offcanvas */
const offcanvas = (element) => {
	const myOffcanvas = document.getElementById(element);
	return new bootstrap.Offcanvas(myOffcanvas);
}

naja.addEventListener('success', e => {
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
			offcanvas(payload[item]).show();
		}
	});
});
