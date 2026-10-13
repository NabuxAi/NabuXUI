import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

const source = (path: string) => fileURLToPath(new URL(path, import.meta.url));

// `vite build` consumes the built packages, exactly as an app would. The dev
// server reads the package sources instead, so edits show up without a rebuild.
export default defineConfig(({ command }) => ({
  plugins: [react()],
  base: './',
  server: { port: 5174, strictPort: true },
  resolve: {
    alias:
      command === 'serve'
        ? [
            { find: '@nabuxai/ui-react/css', replacement: source('../../packages/core/src/css/index.css') },
            { find: /^@nabuxai\/ui-react$/, replacement: source('../../packages/react/src/index.ts') },
            { find: /^@nabuxai\/ui-core$/, replacement: source('../../packages/core/src/js/index.ts') },
          ]
        : [],
  },
}));
