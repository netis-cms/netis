import { registerNajaExtensions } from "./core/base.js";
import Spinner from "./naja/spinner.js";
import InstallWizard from "./naja/install-wizard";
import SubmitButtonDisable from "drago-form/submit-disable";
import "./install.scss";

registerNajaExtensions(
	Spinner,
	SubmitButtonDisable,
	InstallWizard
);
