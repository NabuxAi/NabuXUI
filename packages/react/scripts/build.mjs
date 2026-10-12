// Bundles @nabuxai/ui-react into one ESM file (React and the core stay external),
// emits type declarations, and ships the core stylesheet beside it so apps can
// import '@nabuxai/ui-react/css' without depending on the core package directly.
import { execFileSync } from 'node:child_process';
import { copyFileSync, existsSync, mkdirSync, rmSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { build } from 'esbuild';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const require = createRequire(import.meta.url);

// Same rule as the core build: overwrite dist in place so the showcase build,
// which consumes the built packages, never sees a missing entry file. Only the
// tsc output is cleaned, so deleted sources leave no orphan d.ts.
rmSync(resolve(root, 'dist/types'), { recursive: true, force: true });
mkdirSync(resolve(root, 'dist'), { recursive: true });

await build({
  entryPoints: [resolve(root, 'src/index.ts')],
  bundle: true,
  format: 'esm',
  target: 'es2022',
  jsx: 'automatic',
  outfile: resolve(root, 'dist/index.js'),
  external: ['react', 'react-dom', 'react/jsx-runtime', '@nabuxai/ui-core'],
  // Next.js app router: these are client components.
  banner: { js: '"use client";' },
  logLevel: 'warning',
});

// The gates build the core and this package as one parallel batch, so the
// core stylesheet may not exist yet at this exact moment. Wait for the
// parallel core build to produce it; if it never does (this package built
// alone on a checkout where the core was never built), bundle the core
// stylesheet from its source with the same settings its own build uses.
async function shipCoreStylesheet() {
  const dest = resolve(root, 'dist/nabuxui.css');
  for (let attempt = 0; attempt < 240; attempt += 1) {
    try {
      copyFileSync(require.resolve('@nabuxai/ui-core/nabuxui.css'), dest);
      return;
    } catch {
      await new Promise((wake) => setTimeout(wake, 250));
    }
  }
  await build({
    entryPoints: [resolve(root, '../core/src/css/index.css')],
    bundle: true,
    outfile: dest,
    target: ['chrome120', 'safari17.4', 'firefox128', 'edge120'],
    logLevel: 'warning',
  });
}
await shipCoreStylesheet();
// The declaration pass resolves @nabuxai/ui-core's types through the core's
// dist/types. On a cold checkout that appears only once the parallel core
// build reaches its tsc step, so wait for it instead of racing the batch.
async function waitForCoreTypes() {
  for (let attempt = 0; attempt < 240; attempt += 1) {
    const coreRoot = dirname(require.resolve('@nabuxai/ui-core/package.json'));
    if (existsSync(resolve(coreRoot, 'dist/types/index.d.ts'))) return;
    await new Promise((wake) => setTimeout(wake, 250));
  }
}
await waitForCoreTypes();
execFileSync(resolve(root, 'node_modules/.bin/tsc'), ['-p', resolve(root, 'tsconfig.json')], { stdio: 'inherit' });
console.log('@nabuxai/ui-react built');
