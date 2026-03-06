import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

export default class extends Controller {
    connect() {
        this.boundHandleSubmitEnd = this.handleSubmitEnd.bind(this);
        this.boundBeforeCache = this.beforeCache.bind(this);

        this.element.addEventListener('turbo:submit-end', this.boundHandleSubmitEnd);
        document.addEventListener('turbo:before-cache', this.boundBeforeCache);
    }

    disconnect() {
        this.element.removeEventListener('turbo:submit-end', this.boundHandleSubmitEnd);
        document.removeEventListener('turbo:before-cache', this.boundBeforeCache);
    }

    handleSubmitEnd(event) {
        // Close modal if form submission was successful (no validation errors)
        if (event.detail.success) {
            this.closeModalAndCleanup();
        }
    }

    beforeCache() {
        // Ensure modal is closed before page is cached
        this.closeModalAndCleanup();
    }

    closeModalAndCleanup() {
        const modal = Modal.getInstance(this.element);
        if (modal) {
            modal.hide();
        }

        // Force remove modal backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }

        // Remove modal-open class from body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');

        // Reset form
        const form = this.element.querySelector('form');
        if (form) {
            form.reset();
        }
    }
}
