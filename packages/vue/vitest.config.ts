import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';

// Tests read the core from source, exactly as `vue-tsc -p tsconfig.check.json`
// does — the same rule the react package's vitest.config.ts states: a dist
// that is mid-rebuild must not decide which core the tests exercise.
export default defineConfig({
  plugins: [vue()],
  resolve: {
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
