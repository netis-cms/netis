/* sass */
import '../styles/install.scss';

/* js */
import '../base';

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
