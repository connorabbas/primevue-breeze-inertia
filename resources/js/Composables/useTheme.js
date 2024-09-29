import { ref } from 'vue';
import { useCookies } from '@vueuse/integrations/useCookies';

const { get: getCookie, set: setCookie } = useCookies();
const currentTheme = ref('light');
currentTheme.value = getCookie('selectedTheme') || 'light';

function setTheme(theme) {
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
}

export function useTheme() {
    return { setTheme, currentTheme };
}
