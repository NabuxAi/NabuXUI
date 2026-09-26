# Building NabuXUI blocks

Blocks are the motion-first components that match the catalogue on morphin.dev
(team cards, page-fold menu, pit slider, dot-matrix chart…). They are written
**from scratch**: we reproduce the behaviour and the feel, never their code.

Every block exists three times over one shared core:

| Layer | File | What goes there |
|---|---|---|
| CSS (all frameworks) | `packages/core/src/css/blocks/<group>.css` | Look and motion. Inside `@layer nx.components { … }`. |
| Behaviour (optional) | `packages/core/src/js/blocks/<group>.ts` | Framework-agnostic DOM behaviour: `(el, options) => cleanup`. |
| React / Inertia | `packages/react/src/blocks/<group>.tsx` | The component, thin over the CSS and behaviour. |
| Livewire | `packages/livewire/resources/views/components/<name>.blade.php` + `packages/livewire/resources/js/alpine/blocks/<group>.ts` | Anonymous Blade component + its Alpine data. |
| Showcase | `apps/showcase/src/blocks/<group>.tsx` | Demos for the React build. |
| Playground | `playground/resources/views/demos/<group>.blade.php` | Demos for the Livewire build, at `/blocks/<group>`. |

Only touch your group's files. Index files (`index.ts`, `index.css`, the Alpine
`index.ts`, `App.tsx`) already import every group.

## The feel

- Springs, not durations: `var(--nx-spring-gentle)` + `var(--nx-spring-gentle-duration)`
  (also `snappy`, `bouncy`, `soft`). Plain fades use `--nx-ease-out` / `--nx-dur-*`.
- Every travel distance is multiplied by `var(--nx-motion)` (1, or 0 under
  reduced motion): `translate: 0 calc(12px * var(--nx-motion))`. Reduced motion
  must still work: it becomes a fade. Infinite animations stop or slow down in
  `@media (prefers-reduced-motion: reduce)`.
- Animate `transform`/`translate`/`scale`/`rotate`/`opacity`/`filter`; avoid layout
  properties except where the effect is about size (then keep it small).
- Direction: logical properties everywhere (`inset-inline-start`, `margin-inline-end`,
  `padding-inline`). For motion toward inline-end multiply by `var(--nx-dir)`
  (1 LTR, −1 RTL). Only use physical `left/top` for pointer-measured positions.
- Reveal on scroll: put `data-nx-reveal` (rise | fade | scale | blur | start | end)
  or `data-nx-reveal="group"` (children stagger via `--nx-i`) and call the
  core `reveal(el, { stagger })` (React: `useBehavior(ref, reveal, {...})`,
  Blade: `x-data x-nx-reveal` / `x-nx-reveal.group`). Hidden state CSS:
  `:where(.nx-js) .my-block[data-nx-reveal]:not([data-nx-revealed]) …`.
- Look: tokens only (no hex in components). Surfaces `--nx-surface`, `-surface-2/-3`,
  `--nx-bg`, text `--nx-text`, `-text-muted`, `-text-subtle`, borders `--nx-border`,
  `--nx-border-strong`, accent `--nx-accent`, `--nx-accent-text`, `--nx-accent-soft`,
  `--nx-accent-border`, gold `--nx-gold`, `--nx-gold-text`, `--nx-gold-soft`,
  glow `--nx-glow`, states `--nx-success|warning|danger|info` (+ `-text`, `-soft`),
  glass `--nx-glass`, `--nx-glass-border`, shadows `--nx-shadow-xs…xl`, `--nx-shadow-glow`,
  gradients `--nx-gradient-brand|text|gold|aurora`, radii `--nx-radius-xs…2xl|full`,
  space `--nx-space-1…24`, type `--nx-text-xs…display`, fonts `--nx-font-sans|display|mono`,
  charts `--nx-chart-1…7` (fixed order), palette `--nx-lapis-50…950`, `--nx-violet-*`,
  `--nx-cyan-*`, `--nx-gold-*`, `--nx-ink-*`.
- Both themes: nothing may assume a light or dark background. Check both mentally.
- Letter-spacing: use `--nx-tracking-*` tokens (they are 0 for Persian/Arabic).
- Class names: `nx-<block>` and `nx-<block>-<part>`; state via `data-state`,
  `aria-*`, `data-*`. Private custom properties start with `--_`.

## Polish rules (exact values)

These come from the design-engineering references the project follows. Use the
values as written:

- Press feedback on anything pressable: `scale: calc(1 - 0.04 * var(--nx-motion))`
  on `:active` (= 0.96, never below 0.95), transitioned on `scale` only. A
  `static` prop / `data-static` switches it off where motion would distract.
- Never `transition: all`; name the properties (`transition-property: scale, opacity`).
- Hover motion (lift, scale, tilt) only inside `@media (hover: hover) and (pointer: fine)`;
  colour changes on hover may stay global.
- Nothing enters from `scale(0)`: start at `scale(0.95)` + `opacity: 0` (a
  contextual icon swap is the one exception: `scale 0.25 → 1`, `opacity 0 → 1`,
  `blur(4px) → 0`, `300ms cubic-bezier(0.2, 0, 0, 1)`, both icons kept in the DOM).
- Popovers and menus scale from their trigger (`transform-origin` toward the
  trigger side); modals stay centred.
- UI transitions stay under 300ms (springs may settle longer). Exits are softer
  and faster than enters: a small fixed `translate: 0 -12px` / fade, ~150ms vs ~300ms.
- Stagger 30–80ms between items (`--nx-stagger` is 55ms); never block input while it plays.
- Interactive state changes use transitions (interruptible), keyframes only for
  one-shot sequences and loops.
- Actions triggered from the keyboard many times a day (command palette toggle,
  list navigation) are not animated, or at most 150ms opacity.
- Drag: pointer capture, ignore extra touches after the first, rubber-band
  damping past bounds, and dismiss on a flick (velocity > 0.11 px/ms) even when
  the distance is short.
- Concentric radii: outer radius = inner radius + padding.
- Depth with layered transparent `box-shadow`, borders only for structure.
  Images get `outline: 1px solid` black/white at 10% with `outline-offset: -1px`.
- Motion is never the only cue: every animated state also changes colour, icon or label.

## Accessibility (non-negotiable)

- Native elements first (`button`, `a`, `input type=range|radio|checkbox`, `dialog`,
  `[popover]`, `details`). No `div` buttons.
- Every interactive part is keyboard reachable with a visible `:focus-visible`
  ring (`outline: 2px solid var(--nx-ring); outline-offset: 2px`).
- Split/decorative text: the real text once in `.nx-visually-hidden`, the
  animated copy `aria-hidden="true"`. Split with core `splitText()` (PHP:
  `\NabuXUI\NabuXUI::split()`) and give the pieces container `dir` =
  `textDirection(text)` (PHP: `NabuXUI::direction()`).
- Icons: `<Icon name="…" />` (React) / `{{ \NabuXUI\NabuXUI::icon('…') }}` (Blade),
  decorative unless labelled. Icon names: see `packages/core/src/js/icons.ts`.
- Built-in words via `useT()` (React) / `__('nabuxui::ui.<key>')` (Blade); keys in
  `packages/core/src/js/i18n.ts`. Other copy comes in through props.
- Charts and data visuals ship a visually-hidden table of the same data.

## React conventions

- Import helpers from `../internal/hooks` (`cx`, `useBehavior`, `useControllable`,
  `useInert`, `useWidth`, `useIsoLayoutEffect`, `mergeRefs`, `useEvent`, `useMounted`),
  `../internal/provider` (`SmartLink` for any href, `useT`, `useLocale`),
  `../internal/icon` (`Icon`), and existing components from `../components/*`.
- Core helpers from `'@nabuxai/ui-core'` (reveal, magnetic, tilt, spotlight, indicator,
  place, roveFocus, swipe, leave, splitText, textDirection, scramble, numberParts,
  localeDigits, burst, ripple, copyText, linearScale, niceTicks, linePath, areaPath,
  barPath, bands, donutSegments, nearestIndex, springEasing, springs, theme, toast…).
- Controlled + uncontrolled state: `value` / `defaultValue` / `onValueChange`.
- Accept `className` and pass the rest of the native props to the root.
- SSR-safe: no DOM access during render; effects only.
- React 18 and 19: do not pass `inert` as a prop (use `useInert`), set
  `popovertarget` with `setAttribute` in an effect, `popover="auto"` as a
  lowercase string prop is fine.

## Blade + Alpine conventions

- Anonymous components with `@props([...])`; kebab-case props in markup become
  camelCase in `@props`.
- **Never** use the one-line `@php(...)` form: use `@php … @endphp` blocks only
  (mixing the two breaks Blade's compiler).
- **Never** write `@error="…"` for Alpine (Blade's `@error` directive); use
  `x-on:error`. Prefer `x-on:`/`x-bind:` when in doubt.
- Root element: `{{ $attributes->class('nx-…')->merge([...]) }}` so apps can add
  classes and `wire:*` attributes. Put `wire:model` on the native control.
- State lives in Alpine: `x-data="nxMyBlock(@js($prop), …)"`, registered in your
  `install<Group>Blocks(Alpine)` with `Alpine.data('nxMyBlock', (…) => ({ … }))`.
  Types: `AlpineLike`, `Magics` from `../types` (see `alpine/overlays.ts` for patterns).
- Use Livewire-friendly patterns: `wire:ignore` on parts Alpine rebuilds,
  `@entangle($model)` when a prop should follow a Livewire property.
- Text that animates by piece: `@foreach (\NabuXUI\NabuXUI::split($text, 'char') as $i => $piece)`.

## Checks before you hand back

```bash
cd packages/react && npx tsc -p tsconfig.check.json          # React types
cd packages/livewire && npx tsc -p tsconfig.json             # Alpine types
cd packages/livewire && node scripts/build.mjs               # bundle + CSS build
cd apps/showcase && npx tsc -p tsconfig.json                 # showcase types
cd playground && php artisan view:clear && curl -s -o /dev/null -w "%{http_code}\n" http://localhost:8765/blocks/<group>
```

The last one must print `200` (the Laravel dev server runs on :8765). If it
prints 500, read `playground/storage/logs/laravel.log`.
