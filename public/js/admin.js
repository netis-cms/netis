/* sass */
import '../styles/admin.scss';

/* js */
import '../base';
import Nanobar from 'nanobar';
import PerfectScrollbar from 'perfect-scrollbar';
import 'sidebar-skeleton-compostrap';
import 'sidebar-menu-compostrap';

/* class */
import '../js.class/button.disable';
import '../js.class/bootstrap.offCanvas';

new Nanobar().go(100);
new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new Bootstrap.Alert(element);
});
