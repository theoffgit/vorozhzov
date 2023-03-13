import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/create.js', 'resources/js/list.js', 'resources/js/read.js'],
            refresh: true,
        }),
    ],
});
