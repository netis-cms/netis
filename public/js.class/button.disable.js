/**
 * Disable button running an ajax request.
 */
export default class SubmitButtonDisable {
    initialize(Naja) {
        let submitButton;
        const submitDisable = (doc) => {
            const submit = doc.querySelectorAll('[data-btn-submit]');
            if (submit) {
                submit.forEach(function (button) {
                    button.addEventListener('click', () => submitButton = button);
                });
            }
        };
        submitDisable(document);
        Naja.snippetHandler.addEventListener('afterUpdate', (e) => submitDisable(e.detail.snippet));
        Naja.addEventListener('start', () => {
            if (submitButton) submitButton.disabled = true;
        });
    }
}
