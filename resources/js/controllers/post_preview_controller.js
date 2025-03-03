import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['form'];

    validate(event) {
        if (!this.formTarget.reportValidity()) {
            // Важно! Остановка всплытия события, включая текущий уровень
            // Это нужно для того, чтобы не вызывались остальные обработчики
            event.stopImmediatePropagation();
        }
    }
}
