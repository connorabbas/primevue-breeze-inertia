import { ref } from 'vue';

const currentTheme = ref(localStorage.getItem('selectedTheme') || 'light');

function initSiteTheme() {
    setTheme(currentTheme.value);
}

function setTheme(theme) {
    const domHtml = document.documentElement;
    domHtml.classList.toggle('dark-mode', theme === 'dark');
    saveThemePreference(theme);
}

function saveThemePreference(theme) {
    currentTheme.value = theme;
    localStorage.setItem('selectedTheme', theme);
}

export function useTheme() {
    return { initSiteTheme, setTheme, currentTheme };
}
