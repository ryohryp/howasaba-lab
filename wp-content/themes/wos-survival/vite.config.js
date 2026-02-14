import { defineConfig } from 'vite';
import liveReload from 'vite-plugin-live-reload';
import { resolve } from 'path';

export default defineConfig({
  plugins: [
    liveReload([
      __dirname + '/**/*.php',
    ]),
  ],
  root: '',
  base: process.env.NODE_ENV === 'development'
    ? '/'
    : '/wp-content/themes/wos-frost-fire/dist/',
  build: {
    outDir: resolve(__dirname, 'dist'),
    emptyOutDir: true,
    manifest: true,
    target: 'es2018',
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'assets/js/app.js'),
        style: resolve(__dirname, 'assets/css/app.css'),
      },
    },
  },
  server: {
    cors: true,
    strictPort: true,
    port: 5173,
    hmr: {
      host: 'localhost',
    },
  },
});
