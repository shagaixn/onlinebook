import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // vite.config.js дотор input массивыг шинэчилнэ
input: [
                'resources/css/app.css',
                'resources/css/night-sky.css',
  'resources/js/app.js',
                'resources/js/home.js'
],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
