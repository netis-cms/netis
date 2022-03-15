/* sass */
import '../styles/install.scss';

/* js */
import '../base';
import '../js.class/button.disable';
import SubmitButtonDisable from "../js.class/button.disable";

/* submit button disable */
Naja.registerExtension(new SubmitButtonDisable());

// button disable
const submit = document.getElementById('btn-send');
if (submit) {
    submit.addEventListener('click', (e) => {
        const url = e.target.getAttribute('data-url');
        submit.disabled = true;
        Naja.makeRequest('GET', url, null, {
            history: false,
        }).then();
    });
}
