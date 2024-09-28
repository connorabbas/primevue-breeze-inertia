import '../css/app.css';
import 'primeicons/primeicons.css';

import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';

import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { route as routeFn } from 'ziggy-js';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

import { useTheme } from '@/Composables/useTheme.js';
import customThemePreset from '@/theme-preset.js';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        resolve: (name) => {
            const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
            return pages[`./Pages/${name}.vue`];
        },
        setup({ App, props, plugin }) {
            // set site theme (light/dark mode)
            const { setTheme, currentTheme } = useTheme();
            setTheme(currentTheme.value);

            // Ziggy work around for SSR
            const Ziggy = {
                ...props.initialPage.props.ziggy,
                location: new URL(props.initialPage.props.ziggy.url),
            };
            global.route = (name, params, absolute, config = Ziggy) =>
                routeFn(name, params, absolute, config);

            return (
                createSSRApp({
                    render: () => h(App, props),
                })
                    .use(ZiggyVue, Ziggy)
                    .use(PrimeVue, {
                        theme: customThemePreset,
                    })
                    .use(ToastService)
                    .component('Head', Head)
                    .component('Link', Link)
                    .component('InputText', InputText)
                    .component('Button', Button)
                    .use(plugin)
            );
        },
        progress: {
            color: 'var(--p-primary-500)',
        },
    })
);
