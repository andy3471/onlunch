import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import vue from '@vitejs/plugin-vue';
import eslint from 'vite-plugin-eslint2';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const jsRoot = fileURLToPath(new URL('./resources/js', import.meta.url));

export default defineConfig({
    plugins: [
        eslint(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.ts',
            ],
            refresh: true,
        }),
        wayfinder({
            path: 'resources/js/Generated',
            formVariants: false,
            patterns: ['routes/web.php', 'routes/tenant.php'],
        }),
        vue({
            script: {
                defineModel: true,
                propsDestructure: true,
            },
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: [
            // Longer prefixes first so they win over `@`.
            { find: '@/actions', replacement: path.join(jsRoot, 'Generated/actions') },
            { find: '@/routes', replacement: path.join(jsRoot, 'Generated/routes') },
            { find: '@/wayfinder', replacement: path.join(jsRoot, 'Generated/wayfinder') },
            { find: '@', replacement: jsRoot },
        ],
    },
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
});
