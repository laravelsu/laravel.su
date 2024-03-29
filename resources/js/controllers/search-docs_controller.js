import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['text'];

    initialize() {
        this.intersectionObserver = new IntersectionObserver((entries) => this.processIntersectionEntries(entries));
    }

    connect() {
        this.intersectionObserver.observe(this.element);
    }

    disconnect() {
        this.intersectionObserver.unobserve(this.element);
    }

    processIntersectionEntries(entries) {
        entries.forEach((entry) => {
            this.textTarget.focus();
        });
    }

    search() {
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => {
            this.textTarget.form.requestSubmit();
        }, 340);
    }
}
