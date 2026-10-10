import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        target: 'esnext',
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
            },
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor': ['alpinejs', 'axios', 'chart.js', 'moment'],
                },
                chunkFileNames: 'js/chunks/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
            },
        },
        reportCompressedSize: false,
        chunkSizeWarningLimit: 500,
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
