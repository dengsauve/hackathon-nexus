document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLButtonElement>('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.passwordToggle;

            if (!target) {
                return;
            }

            const input = document.querySelector<HTMLInputElement>(target);

            if (!(input instanceof HTMLInputElement)) {
                return;
            }

            input.type = input.type === 'password' ? 'text' : 'password';
            button.setAttribute('aria-pressed', input.type === 'text' ? 'true' : 'false');
        });
    });

    document.querySelectorAll<HTMLButtonElement>('[data-dismiss]').forEach((button) => {
        button.addEventListener('click', () => {
            const selector = button.dataset.dismiss;

            if (!selector) {
                return;
            }

            button.closest(selector)?.remove();
        });
    });

    document.querySelectorAll<HTMLElement>('[data-cycle-value]').forEach((element) => {
        const rawValues = element.dataset.cycleValue;

        if (!rawValues) {
            return;
        }

        const values = rawValues.split('|');
        let index = 0;

        window.setInterval(() => {
            index = (index + 1) % values.length;
            element.textContent = values[index] ?? '';
        }, 2400);
    });
});
