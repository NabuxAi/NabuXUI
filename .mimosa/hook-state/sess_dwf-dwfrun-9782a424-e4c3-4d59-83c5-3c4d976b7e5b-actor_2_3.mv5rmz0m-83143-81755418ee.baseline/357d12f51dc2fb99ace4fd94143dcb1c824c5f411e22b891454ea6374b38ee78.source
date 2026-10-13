import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

const source = (path: string) => fileURLToPath(new URL(path, import.meta.url));

// The same contract as apps/admin: `vite build` consumes the built packages,
// the dev server reads the package sources so edits show up without a rebuild.
export default defineConfig(({ command }) => ({
  plugins: [vue()],
  base: './',
  server: { port: 5175, strictPort: true },
  resolve: {
    alias:
      command === 'serve'
        ? [
            { find: '@nabuxai/ui-core/css', replacement: source('../../packages/core/src/css/index.css') },
            { find: /^@nabuxai\/ui-core$/, replacement: source('../../packages/core/src/js/index.ts') },
          ]
        : [],
  },
}));
