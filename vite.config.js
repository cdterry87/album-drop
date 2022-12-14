import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    // NOTE: server object here is used to fix an issue with WSL2 on Windows (https://github.com/laravel/vite-plugin/issues/49)
    server: {
        hmr: {
            host: 'localhost',
        },
        // usePolling fix for WSL2 (https://vitejs.dev/config/server-options.html#server-watch)
        // watch: {
        //     usePolling: true 
        // }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
            ],

        }),
    ],
});
