import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// The lib build @nabuxai/ui-react's scripts/build.mjs produces, via Vite: one
// ESM bundle from src/index.ts. Vue (peer) and @nabuxai/ui-core (dependency)
// must stay external — spelled out here because Vite's automatic lib-mode
// externalization was silently inlining both (two Vue runtimes in the app,
// two core toast stores), the same rule ui-react's build pins explicitly.
export default defineConfig({
  plugins: [vue()],
  build: {
    lib: {
      entry: fileURLToPath(new URL('./src/index.ts', import.meta.url)),
      formats: ['es'],
      fileName: () => 'index.js',
    },
    rollupOptions: {
      external: ['vue', /^vue\//, '@nabuxai/ui-core'],
    },
    outDir: 'dist',
    emptyOutDir: true,
  },
});
