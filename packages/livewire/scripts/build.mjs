// Builds everything the Laravel package serves, so apps need no Node step:
//   dist/nabuxui.js    Alpine plugin + @nabuxai/ui-core, one ES module
//   dist/nabuxui.css   the core stylesheet (minified)
//   dist/icons.json    the core icon paths, for <x-nx::icon>
//   dist/head.js       the no-flash theme script, inlined by @nabuxuiHead
//   resources/lang/*/ui.php   the components' own words, from the core table
import { copyFileSync, mkdirSync, writeFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { build } from 'esbuild';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const require = createRequire(import.meta.url);
const core = await import(require.resolve('@nabuxai/ui-core'));

mkdirSync(resolve(root, 'dist'), { recursive: true });

await build({
  entryPoints: [resolve(root, 'resources/js/index.ts')],
  bundle: true,
  format: 'esm',
  target: 'es2022',
  minify: true,
  sourcemap: true,
  outfile: resolve(root, 'dist/nabuxui.js'),
  logLevel: 'warning',
});

copyFileSync(require.resolve('@nabuxai/ui-core/nabuxui.min.css'), resolve(root, 'dist/nabuxui.css'));
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
