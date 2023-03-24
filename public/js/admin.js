/* sass */
import "../styles/admin.scss";

/* js */
import "../base";
import "sidebar-skeleton-compostrap";
import "sidebar-menu-compostrap";
import "../../vendor/ublaboo/datagrid/assets/datagrid";
import "../../vendor/ublaboo/datagrid/assets/datagrid-instant-url-refresh";
import "../naja.components";
import "../naja.tom.select";
import PerfectScrollbar from "perfect-scrollbar";

new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new bootstrap.Alert(element);
});
