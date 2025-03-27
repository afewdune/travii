import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        strictPort: true,
        port: 5173,
        host: '0.0.0.0',
        origin: 'http://localhost:5173',
        cors: true,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
            interval: 100,
            ignored: ['**/node_modules/**', '**/vendor/**'],
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: [
                'resources/**',
                'routes/**',
            ],
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
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
