#!/usr/bin/env node
/**
 * The scene checker (AUTHORING.md §5). Usage:
 *
 *   node packages/motionsites/scripts/check.mjs <slug> [<slug> …] [--no-shots] [--all]
 *
 * Loads scenes/<slug>.html in headless Chromium at 1280×800 and 390×844, in
 * English and Persian (?lang=fa), and fails on uncaught errors, console errors,
 * horizontal overflow, a missing fa → rtl switch, missing reduced-motion
 * handling, Iran-specific content or invalid meta/<slug>.json. Screenshots
 * land in .work/shots/<slug>.<en|fa>.<desk|phone>.png. Exit 1 on any failure.
 *
 * Needs `npm i` in packages/motionsites (playwright-core) and a Chromium:
 * set MS_CHROME to its executable, or the Playwright cache is searched.
 */
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { chromium } from 'playwright-core';

const PKG = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const SHOTS = path.join(PKG, '.work/shots');
fs.mkdirSync(SHOTS, { recursive: true });

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

const flags = new Set(process.argv.slice(2).filter((a) => a.startsWith('--')));
const noShots = flags.has('--no-shots');
let slugs = process.argv.slice(2).filter((a) => !a.startsWith('--'));
if (flags.has('--all')) slugs = fs.readdirSync(path.join(PKG, 'scenes')).filter((f) => f.endsWith('.html')).map((f) => f.slice(0, -5));
if (!slugs.length) {
  console.error('usage: node check.mjs <slug>… [--no-shots] [--all]');
  process.exit(2);
}

// Network noise a scene cannot control (blocked autoplay, flaky CDNs, headless GPU) is not a scene error.
const IGNORED = /Failed to load resource|net::ERR|404|403|CORS|MEDIA_ERR|NotAllowedError|play\(\)|WebGL|GPU|THREE\.WebGLRenderer|Autoplay/i;

const browser = await chromium.launch({
  executablePath: findChrome(),
  args: ['--autoplay-policy=no-user-gesture-required', '--use-gl=angle', '--use-angle=swiftshader', '--enable-unsafe-swiftshader'],
});
let failed = 0;

async function checkOne(slug) {
  const file = path.join(PKG, 'scenes', `${slug}.html`);
  const hard = [];
  const soft = [];
  if (!fs.existsSync(file)) return { hard: [`scene file missing (${file})`], soft };
  const src = fs.readFileSync(file, 'utf8');
  if (!/^<!doctype html>/i.test(src.trimStart())) hard.push('file must start with <!doctype html>');
  if (!/prefers-reduced-motion/.test(src)) hard.push('no prefers-reduced-motion handling in the source');
  if (/\bTehran|\bIran\b|تهران|ایران|اصفهان|شیراز|مشهد|تبریز/.test(src)) hard.push('Iran-specific content found (not allowed)');
  if (/localhost|127\.0\.0\.1/.test(src)) hard.push('localhost reference');

  const metaFile = path.join(PKG, 'meta', `${slug}.json`);
  try {
    const meta = JSON.parse(fs.readFileSync(metaFile, 'utf8'));
    for (const key of ['title', 'oneLiner']) if (!meta[key]?.en || !meta[key]?.fa) hard.push(`meta.${key} needs en + fa`);
    if (typeof meta.js !== 'boolean') hard.push('meta.js must be boolean');
    if (!Array.isArray(meta.props) || meta.props.length < 2) hard.push('meta.props needs ≥2 entries');
    else if (meta.props.some((p) => !p.name || !p.type || p.default === undefined || !p.note?.en || !p.note?.fa)) hard.push('a meta.props entry is incomplete (name, type, default, note.en, note.fa)');
  } catch (error) {
    hard.push(`meta JSON missing/invalid (${metaFile}): ${error.message.slice(0, 80)}`);
  }

  for (const lang of ['en', 'fa']) {
    for (const [vp, viewport] of [['desk', { width: 1280, height: 800 }], ['phone', { width: 390, height: 844 }]]) {
      const ctx = await browser.newContext({ viewport, deviceScaleFactor: 1 });
      const page = await ctx.newPage();
      const errors = [];
      page.on('pageerror', (e) => errors.push('pageerror: ' + e.message.slice(0, 160)));
      page.on('console', (m) => {
        if (m.type() === 'error' && !IGNORED.test(m.text())) errors.push('console: ' + m.text().slice(0, 160));
      });
      try {
        await page.goto(`file://${file}?lang=${lang}`, { waitUntil: 'load', timeout: 25000 });
      } catch (error) {
        soft.push(`${lang}/${vp}: load timeout (${error.message.slice(0, 60)})`);
      }
      await page.waitForTimeout(1800);
      const info = await page
        .evaluate(() => ({
          dir: document.documentElement.dir || getComputedStyle(document.documentElement).direction,
          lang: document.documentElement.lang,
          width: Math.max(document.documentElement.scrollWidth, document.body ? document.body.scrollWidth : 0),
          viewport: innerWidth,
          persian: /[؀-ۿ]/.test(document.body?.innerText || ''),
        }))
        .catch((error) => ({ error: error.message }));
      if (info.error) hard.push(`${lang}/${vp}: evaluate failed ${info.error.slice(0, 80)}`);
      else {
        if (info.width > info.viewport + 2) hard.push(`${lang}/${vp}: horizontal overflow ${info.width} > ${info.viewport}`);
        if (lang === 'fa' && (info.dir !== 'rtl' || !String(info.lang).startsWith('fa'))) hard.push(`fa: <html> must get lang="fa" dir="rtl" (got lang=${info.lang} dir=${info.dir})`);
        if (lang === 'fa' && !info.persian) hard.push('fa: no Persian text rendered');
        if (lang === 'en' && info.dir === 'rtl') hard.push('en: page is rtl');
      }
      for (const error of errors) hard.push(`${lang}/${vp}: ${error}`);
      if (!noShots) await page.screenshot({ path: path.join(SHOTS, `${slug}.${lang}.${vp}.png`) }).catch(() => {});
      await ctx.close();
    }
  }
  return { hard: [...new Set(hard)], soft: [...new Set(soft)] };
}

for (const slug of slugs) {
  const { hard, soft } = await checkOne(slug);
  if (hard.length) failed++;
  console.log(`${hard.length ? '✗' : '✓'} ${slug}${hard.length ? '\n   - ' + hard.join('\n   - ') : ''}${soft.length ? '\n   (warn) ' + soft.join('; ') : ''}`);
}
await browser.close();
if (!noShots) console.log(`screenshots: ${SHOTS}/<slug>.<en|fa>.<desk|phone>.png`);
if (slugs.length > 1) console.log(`${slugs.length - failed}/${slugs.length} passed`);
process.exit(failed ? 1 : 0);
