import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Tooltip from 'primevue/tooltip';

import { useTheme } from '@/Composables/useTheme.js';
import customThemePreset from '@/theme-preset.js';

import Container from './Components/Container.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

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
                theme: customThemePreset,
            })
            .use(ToastService)
            .component('Head', Head)
            .component('Link', Link)
            .component('Container', Container)
            .directive('tooltip', Tooltip)
            .mount(el);
    },
    progress: {
        color: 'var(--p-primary-500)',
    },
});
