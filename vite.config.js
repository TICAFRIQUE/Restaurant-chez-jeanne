import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '192.168.1.5',
        port: 5173,
        strictPort: true,
        cors: true, // ✅ Active CORS
        hmr: {
            host: '192.168.1.5',
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
