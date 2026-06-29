// resources/js/app.js

document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('[data-auto-dismiss="true"]');

    alerts.forEach((alert) => {
        window.setTimeout(() => {
            alert.classList.add('opacity-0', 'translate-y-2');

            window.setTimeout(() => {
                alert.remove();
            }, 300);
        }, 4500);
    });

    const deleteForms = document.querySelectorAll('[data-confirm-delete]');

    deleteForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.getAttribute('data-confirm-delete') || '¿Confirmas esta eliminación?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});