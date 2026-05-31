// these JS + SCSS will be automatically available after installing the package
import { registerNajaExtensions } from "./core/base.js";
import Spinner from "./naja/spinner.js";
import HyperlinkDisable from "./naja/hyperlink-disable.js";

// drago-form extensions
import { PasswordToggle, SubmitButtonDisable } from "drago-form";
import { ToastHandler } from "drago-application";

// page styles
import "./sign-in.scss";

// registration naja extensions
registerNajaExtensions(
	Spinner,
	HyperlinkDisable,
	PasswordToggle,
	SubmitButtonDisable,
	ToastHandler
);
