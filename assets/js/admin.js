import "../base";
import "../naja.components";
import "../naja.tom.select";
import "sidebar-skeleton-compostrap";
import "sidebar-menu-compostrap";
import { Nette } from "live-form-validation";
import naja from "naja";
import Select from "tom-select";
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
import { NajaAjax } from "../../vendor/ublaboo/datagrid/assets/ajax";
import { Dropdown } from "bootstrap";

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
