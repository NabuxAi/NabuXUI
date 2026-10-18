import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';

// The lib build @nabuxai/ui-react's scripts/build.mjs produces, via Vite: one
// ESM bundle from src/index.ts. Svelte (peer) and @nabuxai/ui-core
// (dependency) must stay external: bundling Svelte's client runtime here would
// ship a second copy next to the app's own — internal module state such as
// `active_effect` is not shared across copies, so components created by the
// app's runtime crash at mount (`Cannot read properties of null (reading
// 'nodes')` in assign_nodes). Explicit regexes because Vite's lib-mode
// auto-externalization did not cover compiled subpath imports like
// `svelte/internal/client`.
const external = [/^svelte(\/|$)/, /^@nabuxai\/ui-core(\/|$)/];

export default defineConfig({
  plugins: [svelte()],
  build: {
    lib: {
      entry: fileURLToPath(new URL('./src/index.ts', import.meta.url)),
      formats: ['es'],
      fileName: () => 'index.js',
    },
    rollupOptions: { external },
    outDir: 'dist',
    emptyOutDir: true,
  },
});
