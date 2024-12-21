import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';

import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h } from 'vue';

import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { route as routeFn } from 'ziggy-js';

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue')
            ),
        setup({ App, props, plugin }) {
            // Ziggy work around for SSR
            const Ziggy = {
                ...props.initialPage.props.ziggy,
                location: new URL(page.props.ziggy.location),
            };
            global.route = (name, params, absolute, config = Ziggy) =>
                routeFn(name, params, absolute, config);

            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, Ziggy)
                .use(PrimeVue, {
                    theme: 'none',
                })
                .use(ToastService)
                .component('Head', Head)
                .component('Link', Link)
                .component('InputText', InputText)
                .component('Button', Button);
        },
    })
);
