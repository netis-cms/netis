/**
 * Disable button running an ajax request.
 */
class SubmitButtonDisable {
    initialize(Naja) {
        let submitButton;
        const submitDisable = (el) => {
            const submit = el.querySelector('[data-btn-submit]');
            if (submit) {
                submit.addEventListener('click', () => submitButton = submit);
            }
        };
        submitDisable(document);
        Naja.snippetHandler.addEventListener('afterUpdate', (e) => submitDisable(e.detail.snippet));
        Naja.addEventListener('start', () => {
            if (submitButton) {
                submitButton.disabled = true;
            }
        });
    }
}
Naja.registerExtension(new SubmitButtonDisable());
