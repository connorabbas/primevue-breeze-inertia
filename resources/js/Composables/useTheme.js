import { ref } from 'vue';

const currentTheme = ref('light');

if (import.meta.env.SSR === false) {
    currentTheme.value = localStorage.getItem('selectedTheme') || 'light';
}

function setTheme(theme) {
    if (import.meta.env.SSR === false) {
        const domHtml = document.documentElement;
        domHtml.classList.toggle('dark-mode', theme === 'dark');
        localStorage.setItem('selectedTheme', theme);
    }
    currentTheme.value = theme;
}

export function useTheme() {
    return { setTheme, currentTheme };
}
