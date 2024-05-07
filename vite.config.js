import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
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
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
        extensions: [".js", ".jsx", ".ts", ".tsx", ".json", ".vue"],
    },
    define: {
        "import.meta.env.VITE_PUSHER_APP_KEY": JSON.stringify(
            process.env.VITE_PUSHER_APP_KEY
        ),
        "import.meta.env.VITE_PUSHER_APP_CLUSTER": JSON.stringify(
            process.env.VITE_PUSHER_APP_CLUSTER
        ),
    },
});
