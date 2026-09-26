// Bundles @nabuxai/ui-react into one ESM file (React and the core stay external),
// emits type declarations, and ships the core stylesheet beside it so apps can
// import '@nabuxai/ui-react/css' without depending on the core package directly.
import { execFileSync } from 'node:child_process';
import { copyFileSync, mkdirSync, rmSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { build } from 'esbuild';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const require = createRequire(import.meta.url);

rmSync(resolve(root, 'dist'), { recursive: true, force: true });
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

copyFileSync(require.resolve('@nabuxai/ui-core/nabuxui.css'), resolve(root, 'dist/nabuxui.css'));
execFileSync(resolve(root, 'node_modules/.bin/tsc'), ['-p', resolve(root, 'tsconfig.json')], { stdio: 'inherit' });
console.log('@nabuxai/ui-react built');
