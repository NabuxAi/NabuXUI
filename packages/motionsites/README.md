# @nabuxai/motionsites

The [MotionSites prompt collection](https://motionsitesai-main.vercel.app) (813 AI
web-design prompts: landing pages, sections, HorizonX 3D scenes, 21st.dev and
Superdesign components), rebuilt as live NabuXUI **scenes**. Each scene is a
standalone, bilingual (fa/en, RTL-ready), responsive HTML page.

| path | what |
|---|---|
| `catalog.json` | the source data: slug, group, original prompt, preview media |
| `scenes/{slug}.html` | the scene, one self-contained page (`?lang=fa\|en`, `?theme=dark\|light`) |
| `meta/{slug}.json` | bilingual title, one-liner and important props |
| `thumbs/{slug}.webp` | the gallery card thumbnail |
| `AUTHORING.md` | the contract every scene follows |

## Where they show up

- **Livewire playground:** `/components`, in the five `ms-*` groups. A demo page frames
  the scene (`/motionsites/{slug}.html`) at desktop, tablet or phone width, and shows the original
  prompt and the scene source.
- **React showcase:** the "MotionSites collection" section (`#motionsites-blocks`):
  search, group chips and a full-screen viewer.

## Scripts

```sh
cd packages/motionsites && npm i      # playwright-core, for check + thumbs
node scripts/build.mjs                # regenerate playground manifests + showcase index
node scripts/check.mjs <slug>… | --all  # contract checker (errors, overflow, RTL, meta)
node scripts/thumbs.mjs [--force]     # card thumbnails (needs cwebp)
```

`build.mjs` writes `playground/app/Support/Demos/Ms-*.php` and
`apps/showcase/src/motionsites/index.json`. Both are generated, so never edit them by hand.
