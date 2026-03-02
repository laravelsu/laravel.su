import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    static targets = ['preferred'];

    #mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    #reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

    #userChoice = 'auto'; // кэш текущего выбора пользователя

    connect() {
        this.#userChoice = localStorage.getItem('theme') ?? 'auto';
        this.#applyTheme();
        this.#mediaQuery.addEventListener('change', this.#onSystemChange);
    }

    disconnect() {
        this.#mediaQuery.removeEventListener('change', this.#onSystemChange);
    }

    // ── Actions ─────────────────────────────────────────────────────────

    toggleTheme() {
        const selected = this.preferredTargets.find((el) => el.checked);
        if (!selected) return;

        const choice = selected.value; // "light" | "dark" | "auto"
        this.#userChoice = choice;
        localStorage.setItem('theme', choice);

        this.#applyTheme();
    }

    // ── Приватные методы ────────────────────────────────────────────────

    #applyTheme() {
        const effective = this.#getEffectiveTheme();

        const update = () => {
            document.documentElement.setAttribute('data-bs-theme', effective);
            this.#syncRadios();
        };

        // Плавный переход только если изменилась видимая тема и разрешена анимация
        const oldEffective = document.documentElement.getAttribute('data-bs-theme') || 'light';
        const hasChanged = oldEffective !== effective;
        const canAnimate = hasChanged && !this.#reducedMotionQuery.matches && document.startViewTransition;

        if (canAnimate) {
            document.startViewTransition(update);
        } else {
            update();
        }
    }

    #getEffectiveTheme() {
        if (this.#userChoice === 'auto') {
            return this.#mediaQuery.matches ? 'dark' : 'light';
        }
        return this.#userChoice;
    }

    #syncRadios() {
        // Синхронизируем radio с текущим выбором пользователя (не effective!)
        this.preferredTargets.forEach((el) => {
            el.checked = el.value === this.#userChoice;
        });
    }

    #onSystemChange = () => {
        if (this.#userChoice === 'auto') {
            this.#applyTheme();
        }
    };
}
