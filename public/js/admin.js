/* sass */
import '../styles/admin.scss';

/* js */
import '../base';
import Nanobar from 'nanobar';
import PerfectScrollbar from 'perfect-scrollbar';
import OffCanvas from '../js.class/bootstrap.offCanvas';
import SubmitButtonDisable from '../js.class/button.disable';
import 'sidebar-skeleton-compostrap';
import 'sidebar-menu-compostrap';

new Nanobar().go(100);
new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new Bootstrap.Alert(element);
});

/* submit button disable */
Naja.registerExtension(new SubmitButtonDisable());

/* bootstrap offCanvas */
Naja.registerExtension(new OffCanvas([
	'permissions',
	'privileges',
	'resources',
	'roles',
	'access',
]));
