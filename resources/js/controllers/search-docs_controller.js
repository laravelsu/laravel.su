import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "text", "spinner"]
    /**
     * Port for laravel.com
     */
    connect() {}
    search(event) {
        this.spinnerTarget.classList.remove('d-none');
        event.target.form.requestSubmit();
        setTimeout(() =>  this.spinnerTarget.classList.add('d-none'), 500)
    }
}
