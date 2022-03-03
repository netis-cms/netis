/* sass */
import '../styles/web.scss';

/* js */
import '../base';
import Nanobar from 'nanobar';

/* scripts */
document.addEventListener('DOMContentLoaded', () => {
	new Nanobar().go(100);
});
