import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/usermanagement.css',
                'resources/css/sidebar.css',
                'resources/css/office.css',
                'resources/css/dashboard.css',
                'resources/css/qrpage.css',
                'resources/css/archive.css',
                'resources/js/drag-and-drop.js',
                'resources/css/activity.css',
                'resources/css/analytics.css',
                'resources/css/styles.css',
                'resources/css/documents.css',
                'resources/js/documents.js',
                'resources/css/about.css',
                'resources/js/sidebar.js',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        port: 3000, // Replace with an unused port, e.g., 3000
      },
});
