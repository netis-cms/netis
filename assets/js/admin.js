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

/* scrollbar */
new PerfectScrollbar('.scrollbar', {
	wheelSpeed: 0.3
});

const alertList = document.querySelectorAll('.alert');
const alerts = [].slice.call(alertList).map(function (element) {
	return new Alert(element);
});

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

// Styles
import '../styles/admin.scss';

// Datagrid + UI
document.addEventListener("DOMContentLoaded", () => {
	// Initialize dropdowns
	Array.from(document.querySelectorAll('.dropdown'))
		.forEach(el => new Dropdown(el))

	// Initialize Naja (nette ajax)
	naja.formsHandler.netteForms = Nette;
	naja.initialize();

	// Initialize datagrid
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
});
