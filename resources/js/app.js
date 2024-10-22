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

// Import PrimeVue components
import Select from 'primevue/select';
import MultiSelect from 'primevue/multiselect';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import Divider from 'primevue/divider';
import RadioButton from 'primevue/radiobutton';
import Calendar from 'primevue/calendar';
import Menu from 'primevue/menu';
import Toast from 'primevue/toast';

import { useTheme } from '@/Composables/useTheme.js';
import customThemePreset from '@/theme-preset.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Register plugins
        app.use(plugin);
        app.use(ZiggyVue);
        app.use(PrimeVue, {
            theme: customThemePreset,
        });
        app.use(ToastService);

        // Register components
        app.component('Head', Head);
        app.component('Link', Link);
        app.component('Select', Select);
        app.component('MultiSelect', MultiSelect);
        app.component('InputText', InputText);
        app.component('Button', Button);
        app.component('DataTable', DataTable);
        app.component('Column', Column);
        app.component('InputNumber', InputNumber);
        app.component('Calendar', Calendar);
        app.component('Tag', Tag);
        app.component('Menu', Menu);
        app.component('Toast', Toast);
        app.component('RadioButton', RadioButton);
        app.component('Divider', Divider);

        // Register directives
        app.directive('tooltip', Tooltip);

        // Initialize theme
        const { initSiteTheme } = useTheme();
        initSiteTheme();

        return app.mount(el);
    },
    progress: {
        color: 'var(--p-primary-500)',
    },
});
