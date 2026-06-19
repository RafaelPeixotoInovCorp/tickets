const THEME_KEY = 'kirridesk-theme';

export function getStoredTheme() {
    return localStorage.getItem(THEME_KEY);
}

export function getPreferredTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

export function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.style.colorScheme = theme;
}

export function initTheme() {
    const stored = getStoredTheme();
    const theme = stored === 'dark' || stored === 'light' ? stored : getPreferredTheme();
    applyTheme(theme);
    return theme;
}

export function setTheme(theme) {
    localStorage.setItem(THEME_KEY, theme);
    applyTheme(theme);
}

export function toggleTheme() {
    const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
    setTheme(next);
    return next;
}

export function syncAlpineThemeStore(mode) {
    if (window.Alpine?.store('theme')) {
        window.Alpine.store('theme').mode = mode;
    }
}

window.kirriToggleTheme = () => {
    const mode = toggleTheme();
    syncAlpineThemeStore(mode);
};

// Prevent flash before CSS loads
initTheme();
