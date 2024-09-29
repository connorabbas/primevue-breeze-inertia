import { ref } from 'vue';
import { useCookies } from '@vueuse/integrations/useCookies';

const { get: getCookie, set: setCookie, remove: removeCookie } = useCookies();
const currentTheme = ref('light');

if (import.meta.env.SSR === false) {
    currentTheme.value = getCookie('selectedTheme') || 'light';
}

function setTheme(theme) {
    const themeChanged = theme != currentTheme.value;
    if (import.meta.env.SSR === false) {
        const domHtml = document.documentElement;
        domHtml.classList.toggle('dark-mode', theme === 'dark');
    }

    // set for 1 year
    setCookie('selectedTheme', theme, {
        path: '/',
        maxAge: 60 * 60 * 24 * 365,
    });
    currentTheme.value = theme;

    // reload page, new styles needed
    /* if (import.meta.env.SSR === false && themeChanged) {
        window.location.reload();
    } */
}

export function useTheme() {
    return { setTheme, currentTheme };
}
