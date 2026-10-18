# MotionSites scenes — authoring contract

`@nabuxai/motionsites` is the NabuXUI port of the MotionSites prompt collection
(813 AI web-design prompts). Every prompt becomes a **scene**: one live,
self-contained page that the Livewire playground (`/components/ms-*/{slug}`)
and the React showcase (`#motionsites`) both show in a sandboxed iframe.

`catalog.json` holds the source data (slug, group, original prompt, preview
media). For every entry, an author writes exactly two files:

| file | what |
|---|---|
| `scenes/{slug}.html` | the scene, one standalone HTML document |
| `meta/{slug}.json` | bilingual title / one-liner / important props |

`scripts/build.mjs` generates everything else (playground manifests, the
showcase index) from these two files plus `catalog.json`; never edit the generated outputs.

## 1. The scene file

- **One file, no build step.** It starts with `<!doctype html>` and carries its own
  `<meta charset>`, `<meta name="viewport" content="width=device-width, initial-scale=1">`,
  `<title>`, inline `<style>` and inline `<script>`. Do not use React or JSX:
  write vanilla JS (an ES module is fine). Any framework the prompt names is
  rebuilt by hand. Framer Motion becomes CSS transitions, WAAPI or GSAP;
  lucide-react becomes inline SVG; shadcn becomes hand-written markup.
- **Allowed externals:** Google Fonts stylesheets; pinned-version libraries from
  `cdn.jsdelivr.net/npm/`, `unpkg.com` or `cdnjs.cloudflare.com` (for example
  `three@0.160.0`, `gsap@3.12.5`, `hls.js@1.5.15`); and the media URLs that the
  prompt itself names (Mux/CloudFront/Cloudinary videos and images). Any
  other image must be an inline SVG, a CSS gradient or one of those URLs. Do
  not reference Unsplash URLs that the prompt doesn't name, and never use localhost.
- **Fidelity first.** Rebuild the design the prompt describes: its layout, copy,
  fonts, colours, spacing, motion and interactions. The reference image(s) in
  the brief show what it should look like. The scene should look like that
  design, not like a generic template. Build real behaviour: menus open,
  tabs switch, sliders drag, toggles toggle, forms validate, the 3D scene
  responds to the pointer.
- **HLS video:** load `hls.js` from the CDN for `.m3u8` and use native HLS when
  `video.canPlayType('application/vnd.apple.mpegurl')`. Always set `muted
  playsinline autoplay loop`, and show a poster or gradient fallback so the page
  still reads if the stream fails.
- **WebGL / three.js:** wrap renderer creation in `try/catch` with a static
  fallback, cap `devicePixelRatio` at 2, pause the loop when the tab is hidden or
  the canvas is off-screen (IntersectionObserver), and handle resize.

## 2. Language, direction, theme (the host contract)

- The host appends `?lang=fa` or `?lang=en` (default `en`). Read it **before first
  paint** and set `<html lang dir>`: `fa` → `lang="fa" dir="rtl"`. Every
  visible string exists in English **and** natural Persian (keep brand names
  Latin). The usual way is an `I18N = {en:{…}, fa:{…}}` map applied to
  `[data-i18n]` elements, or two template strings. Persian text uses
  **Vazirmatn** (Google Fonts) as the font, with the design's Latin fonts kept for Latin text.
  Use Persian digits in Persian copy.
- Use **logical properties** (`margin-inline-start`, `inset-inline-end`,
  `text-align: start`, …) so the layout mirrors under RTL. Directional motion
  (marquees, slide-ins) flips with `dir`. Logos, code, numbers and media stay LTR.
- The host may append `?theme=dark|light`. Honour it when the design is
  neutral or has its own theme toggle. A design with a fixed art-directed
  palette (for example a dark cinematic hero) keeps that palette.
- **No Iran-specific content:** no Iranian cities, landmarks, people or flags,
  in Persian copy or anywhere else. Use global or neutral places and names.

## 3. Quality bar

- Responsive from 360px to 1600px. **There must be no horizontal page scroll at 390px.** Mobile
  navs collapse into a working menu.
- Honour `prefers-reduced-motion: reduce`: no autoplaying motion, parallax or
  loops, but the content stays complete and visible. This is required, and the
  checker greps for it.
- The console must show no errors. Guard against null elements and clean up observers. Unused CDN libraries are not allowed.
- Accessible: semantic landmarks, real `<button>`/`<a>`, labels, visible
  focus, `alt`, and `aria-hidden` on decorative layers.
- The scene fills the viewport like a real page (landing pages scroll
  naturally). A small component (a button, a loader, a card) is centred on a
  stage that suits it, with a few real-use variants, not one lonely specimen.

## 4. `meta/{slug}.json`

```json
{
  "title":    { "en": "ClearInvoice video hero", "fa": "هیروی ویدیویی کلیرإینویس" },
  "oneLiner": { "en": "One or two sentences: what it is and what moves.", "fa": "…" },
  "js": true,
  "props": [
    { "name": "--accent", "type": "color", "default": "#FF3300",
      "note": { "en": "What it controls.", "fa": "…" } }
  ]
}
```

- `title`: a real, descriptive name. Replace a bare original title such as "3" or "4"
  with the product or brand and the type, for example "ClearInvoice video hero".
- `js`: `true` when the scene needs script for its behaviour; `false` for pure CSS.
- `props`: 3–5 of the scene's real knobs (CSS custom properties, JS config
  constants, stream URLs, timings, breakpoints), using the names as they appear in the file.

## 5. Check before you finish

```sh
node packages/motionsites/scripts/check.mjs <slug>
```

The checker loads the scene at 1280 and 390 px wide, in `en` and `fa`. It fails
on JS errors, horizontal overflow, a missing RTL switch, missing reduced-motion
handling and invalid meta. It also saves screenshots to `packages/motionsites/.work/shots/`: **Read them** and
compare them against the reference images, then fix what doesn't match.
