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
	Datagrid,
	InlinePlugin,
	ItemDetailPlugin,
	NetteFormsPlugin,
	SelectpickerPlugin,
	SortableJS,
	SortablePlugin,
	TomSelect
} from '../datagrid';
import {TreeViewPlugin} from '../DataGrid/plugins/features/treeView';
import { NajaAjax } from '../DataGrid/ajax';

const dgSelector = "div[data-datagrid-name]";
const dgNaja = new NajaAjax(naja);
const dgAutoSubmitPlugin = new AutosubmitPlugin();
const dgOptions = {
	plugins: [
		dgAutoSubmitPlugin,
		new CheckboxPlugin(),
		new InlinePlugin(),
		new ItemDetailPlugin(),
		new NetteFormsPlugin(Nette),
		new SortablePlugin(new SortableJS()),
		new SelectpickerPlugin(new TomSelect(Select, {plugins: ['remove_button']})),
		new TreeViewPlugin(),
	],
};

/* bootstrap alert */
const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new bootstrap.Alert(element);
});

/* scrollbar */
new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});


/**
 * @param el {HTMLElement}
 */
function datagridInit(el = document.body){
	let parentDg = el.closest(dgSelector);
	if (parentDg) {
		dgAutoSubmitPlugin.onDatagridInit(parentDg._datagrid);
	}

	let grids = el.querySelectorAll(dgSelector);
	for(let grid of grids) {
		if (grid._datagrid) {
			continue;
		}
		grid._datagrid = new Datagrid(grid, dgNaja, dgOptions);
	}
}

document.addEventListener('DOMContentLoaded', () => {
	naja.formsHandler.netteForms = Nette;
	naja.initialize();
	datagridInit();
});

naja.snippetHandler.addEventListener('afterUpdate', e => {
	datagridInit(e.detail.snippet);
});
