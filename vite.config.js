import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { viteStaticCopy } from "vite-plugin-static-copy";
import constants from "./resources/js/Modules/constants.mjs";

const lightTheme = constants.LIGHT_THEME;
const darkTheme = constants.DARK_THEME;

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js", "resources/css/app.css"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        viteStaticCopy({
            targets: [
                {
                    src: `node_modules/primevue/resources/themes/${lightTheme}`,
                    dest: "../themes",
                },
                {
                    src: `node_modules/primevue/resources/themes/${darkTheme}`,
                    dest: "../themes",
                },
            ],
        }),
    ],
});
