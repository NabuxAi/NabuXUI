// Builds @nabuxai/ui-core:
//   1. src/js/tokens.ts      -> src/css/tokens.css   (generated, committed so it can be read and diffed)
//   2. src/css/index.css     -> dist/nabuxui.css, dist/nabuxui.min.css
//   3. src/js/index.ts       -> dist/index.js (ESM) and dist/types/*.d.ts
import { execFileSync } from 'node:child_process';
import { mkdirSync, rmSync, writeFileSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { build } from 'esbuild';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');

// tokens.ts imports its siblings without extensions (as the rest of the source
// does), which Node's own resolver refuses; esbuild bundles it into one module.
const bundled = await build({ entryPoints: [resolve(root, 'src/js/tokens.ts')], bundle: true, format: 'esm', write: false });
const { palette, light, dark, scale, springTokens, reducedMotion } = await import(
  `data:text/javascript;base64,${Buffer.from(bundled.outputFiles[0].text).toString('base64')}`
);

const decl = (entries, indent) => Object.entries(entries).map(([k, v]) => `${indent}--nx-${k}: ${v};`).join('\n');

function tokensCss() {
  const paletteEntries = Object.fromEntries(
    Object.entries(palette).flatMap(([hue, shades]) => Object.entries(shades).map(([step, value]) => [`${hue}-${step}`, value])),
  );

  return `/* Generated from src/js/tokens.ts by scripts/build.mjs — edit the source, not this file. */
@layer nx.tokens {
  :root {
    color-scheme: light dark;
${decl(paletteEntries, '    ')}

${decl(scale, '    ')}

${decl(springTokens, '    ')}

${decl(light, '    ')}
  }

  /* The system says dark and the page has not pinned light. */
  @media (prefers-color-scheme: dark) {
    :root:not([data-theme="light"], .light) {
${decl(dark, '      ')}
    }
  }

  /* Pinned dark: the page (html.dark, as the Nabu Livewire apps set it) or any subtree. */
  [data-theme="dark"], .dark {
    color-scheme: dark;
${decl(dark, '    ')}
  }

  /* Pinned light, after dark so a light island inside a dark page wins. */
  [data-theme="light"], .light {
    color-scheme: light;
${decl(light, '    ')}
  }

  @media (prefers-reduced-motion: reduce) {
    :root {
${decl(reducedMotion, '      ')}
    }
  }
}
`;
}

writeFileSync(resolve(root, 'src/css/tokens.css'), tokensCss());

rmSync(resolve(root, 'dist'), { recursive: true, force: true });
mkdirSync(resolve(root, 'dist'), { recursive: true });

// Browsers that fail a rule we rely on drop only that rule, so the CSS is not
// lowered: nesting, @layer, @starting-style and @property pass through as written.
const cssTarget = ['chrome120', 'safari17.4', 'firefox128', 'edge120'];
await build({ entryPoints: [resolve(root, 'src/css/index.css')], bundle: true, outfile: resolve(root, 'dist/nabuxui.css'), target: cssTarget, logLevel: 'warning' });
await build({ entryPoints: [resolve(root, 'src/css/index.css')], bundle: true, minify: true, outfile: resolve(root, 'dist/nabuxui.min.css'), target: cssTarget, logLevel: 'warning' });

await build({
  entryPoints: [resolve(root, 'src/js/index.ts')],
  bundle: true,
  format: 'esm',
  target: 'es2022',
  outfile: resolve(root, 'dist/index.js'),
  logLevel: 'warning',
});

execFileSync(resolve(root, 'node_modules/.bin/tsc'), ['-p', resolve(root, 'tsconfig.json')], { stdio: 'inherit' });

console.log('@nabuxai/ui-core built');
