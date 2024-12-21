import '../css/tailwind.css';
import '../css/styles.css';
import 'primeicons/primeicons.css';

import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';

import { useTheme } from '@/Composables/useTheme.js';

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
        const { setTheme, currentTheme } = useTheme();
        setTheme(currentTheme.value);

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: 'none',
            })
            .use(ToastService)
            .component('Head', Head)
            .component('Link', Link)
            .mount(el);
    },
    progress: {
        color: 'var(--p-primary-500)',
    },
});
