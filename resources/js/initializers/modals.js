import { Modal } from 'bootstrap';

document.addEventListener('turbo:load', () => {
    document.querySelectorAll('.modal').forEach((modal) => {
        /**
         * Autofocus on modal.
         */
        modal.addEventListener('shown.bs.modal', () => {
            if (modal.querySelector('[autofocus]') !== null) {
                modal.querySelector('[autofocus]').focus();
            }
        });

        modal.addEventListener('hidden.bs.modal', () => {
            setTimeout(() => {
                document.activeElement?.blur();
            });
        });
    });
});

document.addEventListener('turbo:before-cache', () => {
    // Check if the body has the 'modal-open' class
    if (document.body.classList.contains('modal-open')) {
        if (window.location.href.indexOf('begin') > -1) {
            return;
        }

        // Find the currently visible modal
        const element = document.querySelector('.modal.show');

        // Get or create the modal instance
        const modal = Modal.getOrCreateInstance(element);

        // Remove the 'fade' class from the modal element
        element.classList.remove('fade');

        // Disable animation for the modal backdrop
        modal._backdrop._config.isAnimated = false;

        // Hide the modal
        modal.hide();

        // Dispose the modal instance https://getbootstrap.com/docs/5.3/getting-started/javascript/#dispose-method
        // IT hack!
        modal.dispose();
        element.classList.add('fade');
    }
});
