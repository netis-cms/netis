/* sass */
import '../styles/admin.scss';

/* js */
import '../base';
import 'sidebar-skeleton-compostrap';
import 'sidebar-menu-compostrap';
import PerfectScrollbar from 'perfect-scrollbar';
import OffCanvas from '../offcanvas';

new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new Bootstrap.Alert(element);
});

/* bootstrap offCanvas */
Naja.registerExtension(new OffCanvas([
	'permissions',
	'privileges',
	'resources',
	'roles',
	'access',
]));
