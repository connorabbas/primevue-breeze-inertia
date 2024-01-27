import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { viteStaticCopy } from "vite-plugin-static-copy";

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
                    src: "node_modules/primevue/resources/themes/lara-light-indigo",
                    dest: "../themes",
                },
                {
                    src: "node_modules/primevue/resources/themes/lara-dark-indigo",
                    dest: "../themes",
                },
            ],
        }),
    ],
});
