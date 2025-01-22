/* Importy */
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

/* Stylování */
import '../styles/admin.scss';

/* Pluginy pro datagrid */
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

/* Inicializace komponent po načtení DOM */
document.addEventListener("DOMContentLoaded", () => {
	// Inicializace PerfectScrollbar
	initScrollbar('.scrollbar');

	// Inicializace alertů (Bootstrap)
	initAlerts('.alert');

	// Inicializace dropdownů
	initDropdowns('.dropdown');

	// Inicializace Naja (AJAX)
	naja.formsHandler.netteForms = Nette;
	naja.initialize();

	// Inicializace datagrid
	initDatagrid();
});

/* Funkce pro inicializaci PerfectScrollbar */
function initScrollbar(selector) {
	new PerfectScrollbar(selector, { wheelSpeed: 0.3 });
}

/* Funkce pro inicializaci alertů */
function initAlerts(selector) {
	document.querySelectorAll(selector).forEach((element) => {
		new Alert(element);
	});
}

/* Funkce pro inicializaci dropdownů */
function initDropdowns(selector) {
	document.querySelectorAll(selector).forEach((el) => {
		new Dropdown(el);
	});
}

/* Funkce pro inicializaci datagrid */
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
