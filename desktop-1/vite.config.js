import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  root: './',
  base: './', // Use relative paths
  server: {
    host: '127.0.0.1',
    port: 5173,
    strictPort: true,
  },
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    minify: 'esbuild',
    sourcemap: false,
    rollupOptions: {
      input: {
        login: resolve(__dirname, 'renderer/pages/login.html'),
        clientes: resolve(__dirname, 'renderer/pages/clientes.html'),
        configuracoes: resolve(__dirname, 'renderer/pages/configuracoes.html'),
        dashboard: resolve(__dirname, 'renderer/pages/dashboard.html'),
        pedidos: resolve(__dirname, 'renderer/pages/pedidos.html'),
        produtos: resolve(__dirname, 'renderer/pages/produtos.html'),
        teste_api: resolve(__dirname, 'renderer/pages/teste-api.html'),
      },
      external: ['electron'],
    },
  },
  optimizeDeps: {
    exclude: ['electron'],
    include: ['bootstrap'],
  },
});