/* sass */
import "../styles/admin.scss";

/* js */
import "../base";
import "sidebar-skeleton-compostrap";
import "sidebar-menu-compostrap";
import "../naja.components";
import "../naja.tom.select";
import Select from "tom-select";
import PerfectScrollbar from "perfect-scrollbar";
import {
	AutosubmitPlugin,
	CheckboxPlugin,
	ConfirmPlugin,
	createDatagrids,
	InlinePlugin,
	ItemDetailPlugin,
	NetteFormsPlugin,
	SelectpickerPlugin,
	SortableJS,
	SortablePlugin,
	TomSelect
} from "../datagrid";
import {TreeViewPlugin} from "../datagrid/plugins/features/treeView";
import {NajaAjax} from "../datagrid/ajax";

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new bootstrap.Alert(element);
});

/* scrollbar */
new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});

/* datagrid */
function datagridInit(){
	createDatagrids(new NajaAjax(naja), {
		datagrid: {
			plugins: [
				new AutosubmitPlugin(),
				new CheckboxPlugin(),
				new ConfirmPlugin(),
				new InlinePlugin(),
				new ItemDetailPlugin(),
				new NetteFormsPlugin(Nette),
				new SortablePlugin(new SortableJS()),
				new SelectpickerPlugin(new TomSelect(Select)),
				new TreeViewPlugin(),
			],
		},
	});
}


document.addEventListener('DOMContentLoaded', () => {
	naja.formsHandler.netteForms = Nette;
	naja.initialize();
	datagridInit();
});

naja.snippetHandler.addEventListener('afterUpdate', datagridInit);
