document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.querySelector(button.dataset.passwordToggle);

            if (!(input instanceof HTMLInputElement)) {
                return;
            }

            input.type = input.type === 'password' ? 'text' : 'password';
            button.setAttribute('aria-pressed', input.type === 'text' ? 'true' : 'false');
        });
    });

    document.querySelectorAll('[data-dismiss]').forEach((button) => {
        button.addEventListener('click', () => {
            button.closest(button.dataset.dismiss)?.remove();
        });
    });

    document.querySelectorAll('[data-cycle-value]').forEach((element) => {
        const values = element.dataset.cycleValue.split('|');
        let index = 0;

        window.setInterval(() => {
            index = (index + 1) % values.length;
            element.textContent = values[index];
        }, 2400);
    });
});
