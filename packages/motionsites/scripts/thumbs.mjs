#!/usr/bin/env node
/**
 * Card thumbnails for the galleries: thumbs/<slug>.webp (640×400), shot from
 * each scene at 1280×800 in English, 5s after load. Skips existing thumbs
 * unless --force; pass slugs to limit the run. Needs `cwebp` (libwebp) and the
 * same Chromium as check.mjs (MS_CHROME or the Playwright cache).
 *
 *   node packages/motionsites/scripts/thumbs.mjs [--force] [<slug> …]
 */
import { execFileSync } from 'node:child_process';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { chromium } from 'playwright-core';

const PKG = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const OUT = path.join(PKG, 'thumbs');
const TMP = path.join(PKG, '.work/thumb-png');
fs.mkdirSync(OUT, { recursive: true });
fs.mkdirSync(TMP, { recursive: true });

function findChrome() {
  if (process.env.MS_CHROME) return process.env.MS_CHROME;
  const cache = path.join(os.homedir(), 'Library/Caches/ms-playwright');
  const dirs = fs.existsSync(cache) ? fs.readdirSync(cache).filter((d) => d.startsWith('chromium_headless_shell-')).sort().reverse() : [];
  for (const dir of dirs) {
    for (const sub of fs.readdirSync(path.join(cache, dir))) {
      const exe = path.join(cache, dir, sub, 'chrome-headless-shell');
      if (fs.existsSync(exe)) return exe;
    }
  }
  return undefined;
}

const force = process.argv.includes('--force');
let slugs = process.argv.slice(2).filter((a) => !a.startsWith('--'));
if (!slugs.length) slugs = fs.readdirSync(path.join(PKG, 'scenes')).filter((f) => f.endsWith('.html')).map((f) => f.slice(0, -5));
slugs = slugs.filter((s) => force || !fs.existsSync(path.join(OUT, `${s}.webp`)));

const browser = await chromium.launch({
  executablePath: findChrome(),
  args: ['--autoplay-policy=no-user-gesture-required', '--use-gl=angle', '--use-angle=swiftshader', '--enable-unsafe-swiftshader'],
});
let done = 0;
let next = 0;
async function worker() {
  while (next < slugs.length) {
    const slug = slugs[next++];
    const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });
    const png = path.join(TMP, `${slug}.png`);
    try {
      await page.goto(`file://${path.join(PKG, 'scenes', `${slug}.html`)}?lang=en`, { waitUntil: 'load', timeout: 30000 }).catch(() => {});
      await page.waitForTimeout(5000);
      await page.screenshot({ path: png });
      execFileSync('cwebp', ['-quiet', '-q', '72', '-resize', '640', '400', png, '-o', path.join(OUT, `${slug}.webp`)]);
      done++;
    } catch (error) {
      console.warn(`  ! ${slug}: ${error.message.slice(0, 100)}`);
    } finally {
      await page.close();
      fs.rmSync(png, { force: true });
    }
  }
}
await Promise.all(Array.from({ length: 6 }, worker));
await browser.close();
console.log(`thumbs: ${done}/${slugs.length} written to ${OUT}`);
