import "./bootstrap"; // not the framework, terminology
import "primeflex/primeflex.css";
import "primeicons/primeicons.css";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import PrimeVue from "primevue/config";
import { loadTheme, getSavedThemePreference } from './Modules/theme-manager.mjs';

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        // set users theme
        const savedTheme = getSavedThemePreference();
        loadTheme(savedTheme);

        // start the app
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
