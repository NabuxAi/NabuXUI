// Builds everything the Laravel package serves, so apps need no Node step:
//   dist/nabuxui.js    Alpine plugin + @nabuxai/ui-core, one ES module
//   dist/nabuxui.css   the core stylesheet (minified)
//   dist/icons.json    the core icon paths, for <x-nx::icon>
//   dist/head.js       the no-flash theme script, inlined by @nabuxuiHead
//   resources/lang/*/ui.php   the components' own words, from the core table
import { mkdirSync, writeFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { build } from 'esbuild';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const require = createRequire(import.meta.url);
// Icons, i18n and the theme script come from the core source (bundled on the fly).
const bundledCore = await build({ entryPoints: [resolve(root, '../core/src/js/index.ts')], bundle: true, format: 'esm', write: false, logLevel: 'error' });
const core = await import(`data:text/javascript;base64,${Buffer.from(bundledCore.outputFiles[0].text).toString('base64')}`);

mkdirSync(resolve(root, 'dist'), { recursive: true });

await build({
  entryPoints: [resolve(root, 'resources/js/index.ts')],
  bundle: true,
  // Bundle the core from source: the Livewire build never waits on a core build.
  alias: { '@nabuxai/ui-core': resolve(root, '../core/src/js/index.ts') },
  format: 'esm',
  target: 'es2022',
  minify: true,
  sourcemap: true,
  outfile: resolve(root, 'dist/nabuxui.js'),
  logLevel: 'warning',
});

// The stylesheet is bundled from the core's CSS source too.
await build({ entryPoints: [resolve(root, '../core/src/css/index.css')], bundle: true, minify: true, outfile: resolve(root, 'dist/nabuxui.css'), target: ['chrome120', 'safari17.4', 'firefox128', 'edge120'], logLevel: 'warning' });
writeFileSync(resolve(root, 'dist/icons.json'), JSON.stringify(core.icons));
writeFileSync(resolve(root, 'dist/head.js'), core.themeScript);

const php = (value) => `'${String(value).replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'`;
for (const [locale, table] of Object.entries(core.messages)) {
  mkdirSync(resolve(root, `resources/lang/${locale}`), { recursive: true });
  const lines = Object.entries(table).map(([key, text]) => `    ${php(key)} => ${php(text.replace(/\{(\w+)\}/g, ':$1'))},`);
  writeFileSync(
    resolve(root, `resources/lang/${locale}/ui.php`),
    `<?php\n\n// Generated from @nabuxai/ui-core (src/js/i18n.ts) by scripts/build.mjs — edit the source.\n\nreturn [\n${lines.join('\n')}\n];\n`,
  );
}

console.log('nabuxai/nabuxui (Livewire) built');
