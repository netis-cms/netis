/* sass */
import '../styles/admin.scss';

/* js */
import '../base';
import 'sidebar-skeleton-compostrap';
import 'sidebar-menu-compostrap';
import PerfectScrollbar from 'perfect-scrollbar';

new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new Bootstrap.Alert(element);
});

Naja.uiHandler.addEventListener('interaction', (event) => {
	if (event.detail.element.hasAttribute('data-confirm')
		&& ! window.confirm(event.detail.element.getAttribute('data-confirm'))
	) {
		event.preventDefault();
	}
});
