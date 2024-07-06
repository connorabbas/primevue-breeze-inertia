import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { definePreset } from '@primevue/themes';
import Aura from '@primevue/themes/aura';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

import { useTheme } from '@/Composables/useTheme.js';
import themePreset from '@/Modules/theme-preset.mjs';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const siteThemePreset = definePreset(Aura, themePreset);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        // set site theme (light/dark mode)
        const { initSiteTheme } = useTheme();
        initSiteTheme();

        // start the app
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(PrimeVue, {
                theme: {
                    preset: siteThemePreset,
                    options: {
                        darkModeSelector: '.dark-mode',
                        cssLayer: {
                            name: 'primevue',
                            order: 'tailwind-base, primevue, tailwind-utilities',
                        },
                    },
                },
            })
            .use(ToastService)
            .component('Head', Head)
            .component('Link', Link)
            .component('InputText', InputText)
            .component('Button', Button)
            .mount(el);
    },
    progress: {
        color: 'var(--p-primary-500)',
    },
});
