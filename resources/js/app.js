import Alpine from 'alpinejs';
import { getStoredTheme, getPreferredTheme, setTheme, toggleTheme, initTheme } from './theme';

window.Alpine = Alpine;

Alpine.store('theme', {
    mode: initTheme(),

    isDark() {
        return this.mode === 'dark';
    },

    toggle() {
        this.mode = toggleTheme();
    },

    set(mode) {
        if (mode === 'dark' || mode === 'light') {
            this.mode = mode;
            setTheme(mode);
        }
    },

    init() {
        const stored = getStoredTheme();
        this.mode = stored === 'dark' || stored === 'light' ? stored : getPreferredTheme();
    },
});

Alpine.start();
