// these JS + SCSS will be automatically available after installing the package
import { registerNajaExtensions } from "./core/base.js";
import { initAdminTheme } from "./core/admin-theme.js";
import { BootstrapComponents  } from "drago-component";
import ToastHandler from 'drago-application/bootstrap-toast';
import PermissionToggle from "./naja/permission-toggle.js";
import Spinner from "./naja/spinner.js";
import SubmitButtonDisable from "drago-form/submit-disable";
import TomSelectHandler from "drago-form/tom-select";
import DataGrid from "drago-datagrid";

import "./admin.scss";

document.addEventListener("DOMContentLoaded", () => {
	initAdminTheme();
});

new DataGrid().initialize(naja);

registerNajaExtensions(
	BootstrapComponents,
	ToastHandler,
	PermissionToggle,
	Spinner,
	SubmitButtonDisable,
	TomSelectHandler
);
