import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/styles.css',
                'resources/css/dashboard.css',
                'resources/css/login.css',
                'resources/css/register.css',
                'resources/css/search.css',
                'resources/js/main.js',
                'resources/js/dashboard.js',
                'resources/js/login.js',
                'resources/js/register.js',
                'resources/js/search.js',
                'resources/js/searchmain.js',
                'resources/css/autos.css',
                'resources/js/autos.js',
                'resources/css/ayuda.css',
                'resources/js/ayuda.js',
                'resources/css/catalogo-viajes.css',
                'resources/css/admin.css',
                'resources/js/admin.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});