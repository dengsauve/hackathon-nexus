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

    document.querySelectorAll<HTMLElement>('[data-typewriter-words]').forEach((element) => {
        const rawWords = element.dataset.typewriterWords;

        if (!rawWords) {
            return;
        }

        const words = rawWords.split('|').filter(Boolean);

        if (words.length === 0) {
            return;
        }

        let wordIndex = 0;
        let characterIndex = 0;
        let isDeleting = false;
        element.textContent = '';

        const typeNext = () => {
            const word = words[wordIndex] ?? '';

            if (isDeleting) {
                characterIndex -= 1;
            } else {
                characterIndex += 1;
            }

            element.textContent = word.slice(0, characterIndex);

            if (!isDeleting && characterIndex === word.length) {
                isDeleting = true;
                window.setTimeout(typeNext, 1200);

                return;
            }

            if (isDeleting && characterIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                window.setTimeout(typeNext, 250);

                return;
            }

            window.setTimeout(typeNext, isDeleting ? 70 : 120);
        };

        window.setTimeout(typeNext, 1200);
    });

    document.querySelectorAll<HTMLButtonElement>('[data-confirm]').forEach((button) => {
        button.addEventListener('click', (event) => {
            const message = button.dataset.confirm ?? 'Continue?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll<HTMLFormElement>('form').forEach((form) => {
        form.addEventListener('submit', () => {
            form.querySelectorAll<HTMLButtonElement>('button[type="submit"], button:not([type])').forEach((button) => {
                button.dataset.originalText = button.textContent ?? '';
                button.textContent = 'Working...';
                button.disabled = true;
            });
        });
    });
});
