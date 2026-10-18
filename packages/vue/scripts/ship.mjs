// Ships the core stylesheet beside the built bundle so apps can
// import '@nabuxai/ui-vue/css' without depending on the core package directly —
// the same contract @nabuxai/ui-react's scripts/build.mjs implements.
import { copyFileSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const require = createRequire(import.meta.url);
const dest = resolve(root, 'dist/nabuxui.css');
mkdirSync(resolve(root, 'dist'), { recursive: true });

// The gates build the core and this package as one parallel batch, so the core
// stylesheet may not exist yet at this exact moment; wait for it the way the
// react build does.
async function waitForCoreStylesheet() {
  for (let attempt = 0; attempt < 240; attempt += 1) {
    try {
      copyFileSync(require.resolve('@nabuxai/ui-core/nabuxui.css'), dest);
      return true;
    } catch {
      await new Promise((wake) => setTimeout(wake, 250));
    }
  }
  return false;
}

// Cold-checkout fallback: inline the core stylesheet's @import chain in file
// order, so a lone `pnpm --filter @nabuxai/ui-vue build` still produces a
// usable css entry even when the core was never built.
function bundleCoreStylesheet() {
  const coreRoot = dirname(require.resolve('@nabuxai/ui-core/package.json'));
  const seen = new Set();
  const inline = (file) => {
    if (seen.has(file)) return '';
    seen.add(file);
    return readFileSync(file, 'utf8').replace(/@import\s+(?:url\(\s*)?['"]([^'")]+)['"]\s*\)?\s*;/g, (_, spec) =>
      spec.startsWith('.') ? inline(resolve(dirname(file), spec)) : '',
    );
  };
  writeFileSync(dest, inline(resolve(coreRoot, 'src/css/index.css')));
}

if (!(await waitForCoreStylesheet())) bundleCoreStylesheet();
console.log('@nabuxai/ui-vue stylesheet shipped');
