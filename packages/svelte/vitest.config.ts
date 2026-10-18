import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vitest/config';
import { svelte } from '@sveltejs/vite-plugin-svelte';

// Tests read the core from source, exactly as `svelte-check` does through
// tsconfig.json — the same rule the react package's vitest.config.ts states: a
// dist that is mid-rebuild must not decide which core the tests exercise.
// The browser condition is the one svelte's own docs prescribe for vitest, so
// `mount` comes from the client bundle instead of index-server.js.
export default defineConfig({
  plugins: [svelte()],
  resolve: {
    conditions: ['browser'],
    alias: [
      {
        find: /^@nabuxai\/ui-core$/,
        replacement: fileURLToPath(new URL('../core/src/js/index.ts', import.meta.url)),
      },
    ],
  },
  test: {
    environment: 'happy-dom',
  },
});
