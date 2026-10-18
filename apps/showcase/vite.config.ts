import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig, type Plugin } from 'vite';
import react from '@vitejs/plugin-react';

const source = (path: string) => fileURLToPath(new URL(path, import.meta.url));

// `vite build` consumes the built packages, exactly as an app would. The dev
// server reads the package sources instead, so edits show up without a rebuild.
//
// The verification gates build core, react and this app as one parallel batch,
// so a `@nabuxai/*` artifact may not exist yet in the very first instants of a
// cold run. Rather than failing, wait briefly for the parallel build to emit it
// — on a warm tree every import resolves on the first try and this never waits.
function waitForParallelBuilds(): Plugin {
  return {
    name: 'wait-for-parallel-builds',
    async resolveId(source, importer, options) {
      if (!source.startsWith('@nabuxai/')) return null;
      for (let attempt = 0; attempt < 80; attempt += 1) {
        const resolved = await this.resolve(source, importer, { ...options, skipSelf: true });
        if (resolved) return resolved;
        await new Promise((wake) => setTimeout(wake, 250));
      }
      return null;
    },
  };
}


/*
 * The MotionSites scenes (packages/motionsites) sit next to the app under
 * ./motionsites/: {slug}.html, thumbs/{slug}.webp and prompts/{slug}.txt (the
 * original prompt, cut out of catalog.json). The dev server answers them
 * straight from the package; the build copies the ready ones into dist.
 */
function motionsites(): Plugin {
  const root = source('../../packages/motionsites');
  const SLUG = /^[a-z][a-z0-9-]*$/;
  let prompts: Map<string, string> | null = null;
  const prompt = (slug: string) => {
    if (!prompts) {
      prompts = new Map();
      const catalog = JSON.parse(fs.readFileSync(path.join(root, 'catalog.json'), 'utf8')) as Array<{ slug: string; prompt: string }>;
      for (const entry of catalog) prompts.set(entry.slug, entry.prompt);
    }
    return prompts.get(slug);
  };
  const resolve = (url: string): { body: Buffer | string; type: string } | null => {
    const match = /^\/motionsites\/(?:(thumbs|prompts)\/)?([a-z0-9-]+)\.(html|webp|txt)$/.exec(url.split('?')[0]);
    if (!match || !SLUG.test(match[2])) return null;
    const [, dir, slug, ext] = match;
    if (!dir && ext === 'html') {
      const file = path.join(root, 'scenes', `${slug}.html`);
      return fs.existsSync(file) ? { body: fs.readFileSync(file), type: 'text/html; charset=utf-8' } : null;
    }
    if (dir === 'thumbs' && ext === 'webp') {
      const file = path.join(root, 'thumbs', `${slug}.webp`);
      return fs.existsSync(file) ? { body: fs.readFileSync(file), type: 'image/webp' } : null;
    }
    if (dir === 'prompts' && ext === 'txt') {
      const text = prompt(slug);
      return text ? { body: text, type: 'text/plain; charset=utf-8' } : null;
    }
    return null;
  };
  return {
    name: 'nabuxui-motionsites',
    configureServer(server) {
      server.middlewares.use((req, res, next) => {
        const hit = req.url ? resolve(req.url) : null;
        if (!hit) return next();
        res.setHeader('Content-Type', hit.type);
        res.end(hit.body);
      });
    },
    generateBundle() {
      const scenes = path.join(root, 'scenes');
      if (!fs.existsSync(scenes)) return;
      for (const file of fs.readdirSync(scenes)) {
        const slug = file.replace(/\.html$/, '');
        if (!file.endsWith('.html') || !SLUG.test(slug)) continue;
        this.emitFile({ type: 'asset', fileName: `motionsites/${file}`, source: fs.readFileSync(path.join(scenes, file)) });
        const thumb = path.join(root, 'thumbs', `${slug}.webp`);
        if (fs.existsSync(thumb)) this.emitFile({ type: 'asset', fileName: `motionsites/thumbs/${slug}.webp`, source: fs.readFileSync(thumb) });
        const text = prompt(slug);
        if (text) this.emitFile({ type: 'asset', fileName: `motionsites/prompts/${slug}.txt`, source: text });
      }
    },
  };
}

export default defineConfig(({ command }) => ({
  plugins: [react(), waitForParallelBuilds(), motionsites()],
  base: './',
  server: { port: 5199, strictPort: true },
  resolve: {
    alias:
      command === 'serve'
        ? [
            { find: '@nabuxai/ui-react/css', replacement: source('../../packages/core/src/css/index.css') },
            { find: /^@nabuxai\/ui-react$/, replacement: source('../../packages/react/src/index.ts') },
            { find: /^@nabuxai\/ui-core$/, replacement: source('../../packages/core/src/js/index.ts') },
          ]
        : [],
  },
}));
