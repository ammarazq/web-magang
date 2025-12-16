import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // ...other entries if needed
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources'),
        },
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (/\.(png|jpe?g|gif|svg|webp)$/.test(assetInfo.name)) {
                        return 'img/[name][extname]';
                    }
                    if (/\.(woff2?|eot|ttf|otf)$/.test(assetInfo.name)) {
                        return 'fonts/[name][extname]';
                    }
                    return 'assets/[name][extname]';
                },
            },
        },
    },
    server: {
        fs: {
            allow: [
                resolve(__dirname, 'public'),
                resolve(__dirname, 'resources'),
                resolve(__dirname),
            ],
        },
    },
});
