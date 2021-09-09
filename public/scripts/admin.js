/* sass */
import '../scss/admin.scss';

/* js */
import './base';
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
