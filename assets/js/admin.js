/* Imports */
import "../base";
import "../naja.components";
import "../naja.tom.select";
import PerfectScrollbar from "perfect-scrollbar";
import "sidebar-skeleton-compostrap";
import "sidebar-menu-compostrap";
import { Nette } from "live-form-validation";
import naja from "naja";
import Select from "tom-select";
import { NajaAjax } from "../../vendor/ublaboo/datagrid/assets/ajax";
import { Alert, Dropdown } from "bootstrap";

/* Styling */
import '../styles/admin.scss';

/* Datagrid Plugins */
import {
	AutosubmitPlugin,
	CheckboxPlugin,
	ConfirmPlugin,
	createDatagrids,
	DatepickerPlugin,
	Happy,
	HappyPlugin,
	InlinePlugin,
	ItemDetailPlugin,
	NetteFormsPlugin,
	SelectpickerPlugin,
	SortableJS,
	SortablePlugin,
	TomSelect,
	TreeViewPlugin,
	VanillaDatepicker
} from "../../vendor/ublaboo/datagrid/assets"

/* Initialize components after DOM is loaded */
document.addEventListener("DOMContentLoaded", () => {
	// Initialize PerfectScrollbar
	initScrollbar('.scrollbar');

	// Initialize alerts (Bootstrap)
	initAlerts('.alert');

	// Initialize dropdowns
	initDropdowns('.dropdown');

	// Initialize Naja (AJAX)
	naja.formsHandler.netteForms = Nette;
	naja.initialize();

	// Initialize datagrid
	initDatagrid();
});

/* Function to initialize PerfectScrollbar */
function initScrollbar(selector) {
	new PerfectScrollbar(selector, { wheelSpeed: 0.3 });
}

/* Function to initialize alerts */
function initAlerts(selector) {
	document.querySelectorAll(selector).forEach((element) => {
		new Alert(element);
	});
}

/* Function to initialize dropdowns */
function initDropdowns(selector) {
	document.querySelectorAll(selector).forEach((el) => {
		new Dropdown(el);
	});
}

/* Function to initialize datagrid */
function initDatagrid() {
	createDatagrids(new NajaAjax(naja), {
		datagrid: {
			plugins: [
				new AutosubmitPlugin(),
				new CheckboxPlugin(),
				new ConfirmPlugin(),
				new InlinePlugin(),
				new ItemDetailPlugin(),
				new NetteFormsPlugin(Nette),
				new HappyPlugin(new Happy()),
				new SortablePlugin(new SortableJS()),
				new DatepickerPlugin(new VanillaDatepicker({ buttonClass: 'btn' })),
				new SelectpickerPlugin(new TomSelect(Select)),
				new TreeViewPlugin(),
			],
		},
	});
}
