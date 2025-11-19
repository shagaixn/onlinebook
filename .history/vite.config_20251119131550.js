import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // vite.config.js дотор input массивыг шинэчилнэ
input: [
  'resources/css/app.css',
  'resources/js/app.js',
  'resources/js/home.js',
  'resources/js/sky.js'
],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
