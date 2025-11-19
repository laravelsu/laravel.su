import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['form', 'input'];

    connect() {
        this.timeout = null;
    }

    search() {
        clearTimeout(this.timeout);

        this.timeout = setTimeout(() => {
            this.formTarget.requestSubmit();
        }, 300);
    }
}
