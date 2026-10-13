import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

const source = (path: string) => fileURLToPath(new URL(path, import.meta.url));

// The same contract as apps/showcase and apps/admin: `vite build` consumes the
// built packages exactly as an app would; the dev server reads the package
// sources instead, so edits show up without a rebuild.
//
// The build lands in dist/ and is served by Laravel from the public `inertia/`
// path (the integrator copies dist → public/inertia). That is why:
//   - base is './' — every asset URL is relative to wherever it is copied;
//   - the manifest is on — the root Blade view resolves the hashed entry from
//     dist/.vite/manifest.json instead of guessing file names.
export default defineConfig(({ command }) => ({
  plugins: [react()],
  base: './',
  server: { port: 5177, strictPort: true },
  build: { manifest: true },
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
