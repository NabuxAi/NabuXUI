import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vitest/config';

// Tests read the core from source, exactly as `tsc -p tsconfig.check.json`
// does: the gate batch rebuilds packages/core/dist while the tests run, and a
// dist that is mid-rebuild must not decide which core the tests exercise.
export default defineConfig({
  resolve: {
    alias: [
      {
        find: /^@nabuxai\/ui-core$/,
        replacement: fileURLToPath(new URL('../core/src/js/index.ts', import.meta.url)),
      },
    ],
  },
});
