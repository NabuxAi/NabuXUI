import { fileURLToPath } from 'node:url';
import { defineConfig, type Plugin } from 'vite';
import react from '@vitejs/plugin-react';

const source = (path: string) => fileURLToPath(new URL(path, import.meta.url));

// `vite build` consumes the built packages, exactly as an app would. The dev
// server reads the package sources instead, so edits show up without a rebuild.
//
// The verification gates build core, react and this app as one parallel batch,
// so a `@nabuxai/*` artifact may not exist yet in the very first instants of a
// cold run. Rather than failing, wait briefly for the parallel build to emit it
// — on a warm tree every import resolves on the first try and this never waits.
function waitForParallelBuilds(): Plugin {
  return {
    name: 'wait-for-parallel-builds',
    async resolveId(source, importer, options) {
      if (!source.startsWith('@nabuxai/')) return null;
      for (let attempt = 0; attempt < 80; attempt += 1) {
        const resolved = await this.resolve(source, importer, { ...options, skipSelf: true });
        if (resolved) return resolved;
        await new Promise((wake) => setTimeout(wake, 250));
      }
      return null;
    },
  };
}

export default defineConfig(({ command }) => ({
  plugins: [react(), waitForParallelBuilds()],
  base: './',
  server: { port: 5199, strictPort: true },
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
