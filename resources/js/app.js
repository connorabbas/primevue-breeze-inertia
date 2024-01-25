import "./bootstrap"; // not the framework, terminology
import "primeflex/primeflex.css";
import "primevue/resources/themes/lara-light-indigo/theme.css";
import "primeicons/primeicons.css";
import "../css/app.css";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import PrimeVue from "primevue/config";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

// TODO: theme toggle
function loadTheme(themeName) {
    const themeUrl = `/node_modules/primevue/resources/themes/${themeName}/theme.css`;
    let link = document.getElementById('primevue-theme-css');
    if (!link) {
        link = document.createElement('link');
        link.id = 'primevue-theme-css';
        link.rel = 'stylesheet';
        link.type = 'text/css';
        document.head.appendChild(link);
    }
    link.href = themeUrl;
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        //loadTheme('lara-light-indigo');
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(PrimeVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
