export default class SubmitButtonDisable {
    initialize(naja) {
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
        naja.snippetHandler.addEventListener('afterUpdate', (e) => submitDisable(e.detail.snippet));
        naja.addEventListener('start', () => {
            if (submitButton) submitButton.disabled = true;
        });
    }
}
