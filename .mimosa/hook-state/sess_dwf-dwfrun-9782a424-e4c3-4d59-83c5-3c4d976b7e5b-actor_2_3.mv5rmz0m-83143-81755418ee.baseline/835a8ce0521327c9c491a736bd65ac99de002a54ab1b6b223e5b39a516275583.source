{{--
    The 3D icon set on a Northlight product page: twelve hand-built inline-SVG
    icons (soft gradients, white edge highlight, elliptical ground shadow) in a
    grid that tilts with the pointer, a segment that recolours the whole set
    (violet · amber · mint), and the same language at three sizes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .i3ds-root {
        --i3ds-c1: #c7b8ff; --i3ds-c2: #6c4cf1;
        --i3ds-c3: #8df0ff; --i3ds-c4: #0ea5e9;
        --i3ds-deep: color-mix(in oklab, var(--i3ds-c2) 55%, #14123a);
        --i3ds-ink: #2a1e5e; --i3ds-plate: color-mix(in oklab, var(--i3ds-c1) 12%, transparent);
        display: grid; gap: 2rem; justify-items: center;
    }
    .i3ds-root[data-theme='amber'] { --i3ds-c1: #ffe9a8; --i3ds-c2: #f59e0b; --i3ds-c3: #c9f2ff; --i3ds-c4: #38bdf8; --i3ds-ink: #6b4a05; }
    .i3ds-root[data-theme='mint']  { --i3ds-c1: #b7f5dc; --i3ds-c2: #10b981; --i3ds-c3: #e3e0ff; --i3ds-c4: #8b5cf6; --i3ds-ink: #0b5c43; }
    html[data-theme="dark"] .i3ds-root { --i3ds-ink: #e7e4ff; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .i3ds-root { --i3ds-ink: #e7e4ff; }
    }
    .i3ds-defs { position: absolute; inline-size: 0; block-size: 0; overflow: hidden; pointer-events: none; }
    .i3ds-root .i3ds-s1 { stop-color: var(--i3ds-c1); }
    .i3ds-root .i3ds-s2 { stop-color: var(--i3ds-c2); }
    .i3ds-root .i3ds-s3 { stop-color: var(--i3ds-c3); }
    .i3ds-root .i3ds-s4 { stop-color: var(--i3ds-c4); }
    .i3ds-root .i3ds-s5 { stop-color: var(--i3ds-deep); }
    .i3ds-root .i3ds-sh { fill: url(#i3ds-shadow); }
    .i3ds-head { display: grid; gap: .4rem; justify-items: center; text-align: center; }
    .i3ds-head p { margin: 0; max-inline-size: 54ch; color: var(--nx-text-muted); }
    .i3ds-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px;
                background: var(--i3ds-plate); border: 1px solid color-mix(in oklab, var(--i3ds-c2) 22%, transparent); }
    .i3ds-seg button { display: inline-flex; align-items: center; gap: .45rem; appearance: none; border: none;
                       border-radius: 999px; padding: .42rem 1rem; background: transparent; cursor: pointer;
                       font: 500 .8125rem/1 var(--nx-font, inherit); color: var(--i3ds-ink);
                       transition: background-color .18s ease, color .18s ease, box-shadow .18s ease; }
    .i3ds-seg button i { inline-size: .85rem; aspect-ratio: 1; border-radius: 50%; box-shadow: inset 0 -1px 2px #ffffff8c; }
    .i3ds-seg button[aria-checked="true"] { background: linear-gradient(135deg, var(--i3ds-c1), var(--i3ds-c2)); color: #fff;
                                             box-shadow: 0 3px 10px color-mix(in oklab, var(--i3ds-c2) 45%, transparent); }
    .i3ds-seg button:focus-visible { outline: 2px solid var(--i3ds-c2); outline-offset: 2px; }
    .i3ds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6rem, 1fr)); gap: .75rem;
                 inline-size: 100%; max-inline-size: 46rem; }
    .i3ds-cell { display: grid; justify-items: center; gap: .55rem; padding: 1.05rem .5rem .9rem; border-radius: 1.1rem;
                 background: var(--i3ds-plate); cursor: default;
                 transform: perspective(38rem) rotateX(var(--i3ds-rx, 0deg)) rotateY(var(--i3ds-ry, 0deg));
                 transition: transform .18s ease, background-color .18s ease; }
    .i3ds-cell:hover { background: color-mix(in oklab, var(--i3ds-c1) 24%, transparent); }
    .i3ds-cell:focus-within { outline: 2px solid var(--i3ds-c2); outline-offset: 2px; }
    .i3ds-cell .i3ds-icon { inline-size: 4rem; block-size: 4rem; transition: translate .18s ease, filter .18s ease; }
    .i3ds-cell:hover .i3ds-icon { translate: 0 -4px; filter: drop-shadow(0 16px 12px rgb(15 23 42 / .16)); }
    .i3ds-name { font-size: .78rem; font-weight: 600; color: var(--i3ds-ink); letter-spacing: .01em; }
    .i3ds-meta { margin: 0; font-size: .78rem; color: var(--nx-text-muted); }
    .i3ds-sizes { display: flex; flex-wrap: wrap; align-items: end; justify-content: center; gap: 1.25rem 2.25rem; }
    .i3ds-size { display: grid; gap: .6rem; justify-items: center; }
    .i3ds-size > small { font-size: .72rem; color: var(--nx-text-muted); }
    .i3ds-chip { display: inline-flex; align-items: center; gap: .55rem; border: none; cursor: pointer;
                 padding: .6rem 1.15rem; border-radius: 999px; color: #fff;
                 background: linear-gradient(135deg, var(--i3ds-c1), var(--i3ds-c2));
                 font: 600 .875rem/1 var(--nx-font, inherit);
                 box-shadow: 0 4px 14px color-mix(in oklab, var(--i3ds-c2) 38%, transparent); }
    .i3ds-chip svg { inline-size: 1.375rem; block-size: 1.375rem; }
    .i3ds-field { display: inline-flex; align-items: center; gap: .6rem; inline-size: min(15rem, 74vw);
                  padding: .55rem 1rem; border-radius: .9rem; background: var(--i3ds-plate);
                  border: 1px solid color-mix(in oklab, var(--i3ds-c2) 22%, transparent);
                  color: var(--i3ds-ink); font-size: .85rem; }
    .i3ds-field svg { inline-size: 1.5rem; block-size: 1.5rem; flex: none; }
    .i3ds-display svg { inline-size: 6.75rem; block-size: 6.75rem; }
    .i3ds-row { display: inline-flex; align-items: center; gap: .7rem; padding: .55rem .95rem; border-radius: .9rem;
                background: var(--i3ds-plate); color: var(--i3ds-ink); font-size: .85rem; font-weight: 600; }
    .i3ds-row svg { inline-size: 2.35rem; block-size: 2.35rem; }
    @media (max-width: 480px) {
        .i3ds-grid { grid-template-columns: repeat(auto-fill, minmax(4.9rem, 1fr)); gap: .5rem; }
        .i3ds-sizes { gap: 1rem 1.25rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .i3ds-root *, .i3ds-root *::before, .i3ds-root *::after {
            transition-duration: .01ms !important; animation: none !important;
        }
        .i3ds-cell { transform: none !important; }
        .i3ds-cell:hover .i3ds-icon { translate: none; }
    }
</style>

<div class="i3ds-root"
    x-data="{
        theme: 'violet',
        tilt(e) {
            const el = e.currentTarget, r = el.getBoundingClientRect();
            el.style.setProperty('--i3ds-rx', ((0.5 - (e.clientY - r.top) / r.height) * 12).toFixed(2) + 'deg');
            el.style.setProperty('--i3ds-ry', (((e.clientX - r.left) / r.width - 0.5) * 14).toFixed(2) + 'deg');
        },
        reset(e) {
            e.currentTarget.style.removeProperty('--i3ds-rx');
            e.currentTarget.style.removeProperty('--i3ds-ry');
        },
    }"
    :data-theme="theme">

    {{-- one hidden defs block colours every icon below; nothing loads from outside --}}
    <svg class="i3ds-defs" width="0" height="0" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="i3ds-body" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" class="i3ds-s1"/><stop offset="1" class="i3ds-s2"/>
            </linearGradient>
            <linearGradient id="i3ds-accent" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" class="i3ds-s3"/><stop offset="1" class="i3ds-s4"/>
            </linearGradient>
            <linearGradient id="i3ds-gloss" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#fff" stop-opacity=".85"/><stop offset="1" stop-color="#fff" stop-opacity="0"/>
            </linearGradient>
            <radialGradient id="i3ds-shadow">
                <stop offset="0" stop-color="#0f172a" stop-opacity=".26"/>
                <stop offset=".65" stop-color="#0f172a" stop-opacity=".12"/>
                <stop offset="1" stop-color="#0f172a" stop-opacity="0"/>
            </radialGradient>
        </defs>
    </svg>

    <section class="pg-box" style="justify-items: center">
        <div class="i3ds-head">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Twelve icons, one language', 'دوازده آیکون، یک زبان') }}</h3>
            <p>
                {{ $say('Every icon is pure inline SVG on a 64×64 grid: two shared gradients, a white edge highlight and an elliptical ground shadow. Move the pointer across a tile — it tilts with you — and flip the segment to recolour the whole set.', 'هر آیکون SVG درون‌خطیِ خالص روی شبکهٔ ۶۴×۶۴ است: دو گرادیان مشترک، هایلایت لبهٔ سفید و سایهٔ بیضی زیر شیء. اشاره‌گر را روی کاشی بچرخانید — با شما کج می‌شود — و سگمنت را بزنید تا کل مجموعه رنگ عوض کند.') }}
            </p>
        </div>

        <div class="i3ds-seg" role="radiogroup" aria-label="{{ $say('Colour theme', 'تم رنگی') }}">
            <button type="button" role="radio" :aria-checked="theme === 'violet'" x-on:click="theme = 'violet'">
                <i aria-hidden="true" style="background: linear-gradient(135deg, #c7b8ff, #6c4cf1)"></i>{{ $say('Violet', 'بنفش') }}
            </button>
            <button type="button" role="radio" :aria-checked="theme === 'amber'" x-on:click="theme = 'amber'">
                <i aria-hidden="true" style="background: linear-gradient(135deg, #ffe9a8, #f59e0b)"></i>{{ $say('Amber', 'کهربایی') }}
            </button>
            <button type="button" role="radio" :aria-checked="theme === 'mint'" x-on:click="theme = 'mint'">
                <i aria-hidden="true" style="background: linear-gradient(135deg, #b7f5dc, #10b981)"></i>{{ $say('Mint', 'نعنایی') }}
            </button>
        </div>

        <div class="i3ds-grid">
            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Rocket', 'موشک') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="55" rx="15" ry="4.5"/>
                    <path d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z" fill="url(#i3ds-accent)"/>
                    <path d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z" fill="url(#i3ds-accent)"/>
                    <path d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z" fill="url(#i3ds-accent)"/>
                    <path d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z" fill="url(#i3ds-body)"/>
                    <circle cx="32" cy="26" r="5.5" fill="url(#i3ds-accent)"/>
                    <circle cx="30.2" cy="24.2" r="1.6" fill="#fff" opacity=".8"/>
                    <ellipse cx="27.5" cy="20" rx="2.3" ry="6" transform="rotate(14 27.5 20)" fill="url(#i3ds-gloss)"/>
                </svg>
                <span class="i3ds-name">{{ $say('Rocket', 'موشک') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Chart', 'نمودار') }}">
                    <ellipse class="i3ds-sh" cx="34" cy="55" rx="17" ry="4"/>
                    <polygon points="23,38 27,34 27,48 23,52" fill="url(#i3ds-accent)"/>
                    <polygon points="13,38 17,34 27,34 23,38" fill="#fff" opacity=".5"/>
                    <rect x="13" y="38" width="10" height="14" rx="2" fill="url(#i3ds-body)"/>
                    <polygon points="37,28 41,24 41,48 37,52" fill="url(#i3ds-accent)"/>
                    <polygon points="27,28 31,24 41,24 37,28" fill="#fff" opacity=".5"/>
                    <rect x="27" y="28" width="10" height="24" rx="2" fill="url(#i3ds-body)"/>
                    <polygon points="51,18 55,14 55,48 51,52" fill="url(#i3ds-accent)"/>
                    <polygon points="41,18 45,14 55,14 51,18" fill="#fff" opacity=".5"/>
                    <rect x="41" y="18" width="10" height="34" rx="2" fill="url(#i3ds-body)"/>
                </svg>
                <span class="i3ds-name">{{ $say('Chart', 'نمودار') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Shield', 'سپر') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="54" rx="16" ry="4"/>
                    <path d="M32 8l18 6.5V29c0 11.5-7.4 19.9-18 25.2C23.4 48.9 14 40.5 14 29V14.5Z" fill="url(#i3ds-body)"/>
                    <path d="M25 29l5.2 5.2L40 24" fill="none" stroke="#fff" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" opacity=".9"/>
                    <path d="M24.5 12.7 32 10l7.5 2.7" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" opacity=".5"/>
                </svg>
                <span class="i3ds-name">{{ $say('Shield', 'سپر') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Cloud', 'ابر') }}">
                    <ellipse class="i3ds-sh" cx="31" cy="52.5" rx="15" ry="4"/>
                    <path d="M17.5 43a7.5 7.5 0 0 1-1.2-14.9 12.5 12.5 0 0 1 24.2-3.4A9 9 0 0 1 44.5 43Z" fill="url(#i3ds-body)"/>
                    <path d="M25 26.5a9.5 9.5 0 0 1 9-4.5" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" opacity=".55"/>
                    <path d="M20 43h21.5a9 9 0 0 0 2.7-.42A9 9 0 0 1 44.5 43Z" fill="url(#i3ds-accent)" opacity=".3"/>
                </svg>
                <span class="i3ds-name">{{ $say('Cloud', 'ابر') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Message', 'پیام') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="54.5" rx="16" ry="4"/>
                    <path d="M20 41l-5.5 11.5L28 44Z" fill="url(#i3ds-accent)"/>
                    <rect x="12" y="12" width="40" height="32" rx="10" fill="url(#i3ds-body)"/>
                    <circle cx="24" cy="28" r="3.2" fill="url(#i3ds-accent)"/>
                    <circle cx="32" cy="28" r="3.2" fill="url(#i3ds-accent)"/>
                    <circle cx="40" cy="28" r="3.2" fill="url(#i3ds-accent)"/>
                    <circle cx="23" cy="26.8" r="1" fill="#fff" opacity=".8"/>
                    <circle cx="31" cy="26.8" r="1" fill="#fff" opacity=".8"/>
                    <rect x="16" y="16" width="21" height="6.5" rx="3.25" fill="url(#i3ds-gloss)" opacity=".75"/>
                </svg>
                <span class="i3ds-name">{{ $say('Message', 'پیام') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Gear', 'چرخ‌دنده') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="55" rx="15" ry="4"/>
                    <g fill="url(#i3ds-body)">
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(45 32 32)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(90 32 32)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(135 32 32)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(180 32 32)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(225 32 32)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(270 32 32)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(315 32 32)"/>
                    </g>
                    <circle cx="32" cy="32" r="14" fill="url(#i3ds-body)"/>
                    <circle cx="32" cy="32" r="5.5" fill="url(#i3ds-accent)"/>
                    <circle cx="30.2" cy="30.2" r="1.7" fill="#fff" opacity=".85"/>
                    <path d="M22 25a12.5 12.5 0 0 1 7-6.5" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" opacity=".5"/>
                </svg>
                <span class="i3ds-name">{{ $say('Gear', 'چرخ‌دنده') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Lock', 'قفل') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="53.5" rx="15" ry="4"/>
                    <path d="M23 28v-6a9 9 0 0 1 18 0v6" fill="none" stroke="url(#i3ds-accent)" stroke-width="6.5" stroke-linecap="round"/>
                    <rect x="16" y="27" width="32" height="23" rx="8" fill="url(#i3ds-body)"/>
                    <circle cx="32" cy="37.5" r="4.6" fill="url(#i3ds-accent)"/>
                    <rect x="29.8" y="39.5" width="4.4" height="7" rx="2.2" fill="url(#i3ds-accent)"/>
                    <rect x="20" y="30.5" width="14" height="6" rx="3" fill="url(#i3ds-gloss)" opacity=".7"/>
                </svg>
                <span class="i3ds-name">{{ $say('Lock', 'قفل') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Camera', 'دوربین') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="53.5" rx="17" ry="4"/>
                    <path d="M23.5 20l3-6h11l3 6Z" fill="url(#i3ds-accent)"/>
                    <rect x="10" y="20" width="44" height="29" rx="8" fill="url(#i3ds-body)"/>
                    <circle cx="32" cy="34.5" r="10.5" fill="url(#i3ds-accent)"/>
                    <circle cx="32" cy="34.5" r="7" fill="url(#i3ds-gloss)" opacity=".28"/>
                    <circle cx="29" cy="31.8" r="2.2" fill="#fff" opacity=".85"/>
                    <circle cx="45.5" cy="26.5" r="2" fill="url(#i3ds-accent)"/>
                    <rect x="14" y="23.5" width="16" height="6" rx="3" fill="url(#i3ds-gloss)" opacity=".7"/>
                </svg>
                <span class="i3ds-name">{{ $say('Camera', 'دوربین') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Battery', 'باتری') }}">
                    <ellipse class="i3ds-sh" cx="33" cy="53.5" rx="16" ry="4"/>
                    <rect x="48.5" y="27" width="5" height="10" rx="2.5" fill="url(#i3ds-accent)"/>
                    <rect x="11" y="22" width="37.5" height="22" rx="6" fill="url(#i3ds-body)"/>
                    <rect x="16" y="27" width="6.5" height="12" rx="2.5" fill="url(#i3ds-accent)"/>
                    <rect x="25" y="27" width="6.5" height="12" rx="2.5" fill="url(#i3ds-accent)"/>
                    <rect x="34" y="27" width="6.5" height="12" rx="2.5" fill="url(#i3ds-accent)"/>
                    <rect x="15" y="25" width="13" height="5.5" rx="2.75" fill="url(#i3ds-gloss)" opacity=".7"/>
                </svg>
                <span class="i3ds-name">{{ $say('Battery', 'باتری') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Search', 'جست‌وجو') }}">
                    <ellipse class="i3ds-sh" cx="31" cy="54.5" rx="14" ry="4"/>
                    <path d="M39.5 39.5 52 52" stroke="url(#i3ds-accent)" stroke-width="7.5" stroke-linecap="round"/>
                    <circle cx="27.5" cy="27.5" r="16.5" fill="url(#i3ds-body)"/>
                    <circle cx="27.5" cy="27.5" r="9.5" fill="url(#i3ds-accent)"/>
                    <circle cx="27.5" cy="27.5" r="9.5" fill="url(#i3ds-gloss)" opacity=".3"/>
                    <path d="M16.2 19.7a14.5 14.5 0 0 1 9.3-6.3" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" opacity=".6"/>
                </svg>
                <span class="i3ds-name">{{ $say('Search', 'جست‌وجو') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Folder', 'پوشه') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="53.5" rx="17" ry="4"/>
                    <path d="M12 25a4 4 0 0 1 4-4h10.5l5 5H48a4 4 0 0 1 4 4v14a4 4 0 0 1-4 4H16a4 4 0 0 1-4-4Z" fill="url(#i3ds-accent)"/>
                    <rect x="12" y="31" width="40" height="4" rx="2" fill="#fff" opacity=".4"/>
                    <path d="M12 35h40v9a4 4 0 0 1-4 4H16a4 4 0 0 1-4-4Z" fill="url(#i3ds-body)"/>
                    <rect x="15" y="38" width="13" height="4" rx="2" fill="url(#i3ds-gloss)" opacity=".7"/>
                </svg>
                <span class="i3ds-name">{{ $say('Folder', 'پوشه') }}</span>
            </div>

            <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                <svg class="i3ds-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('Star', 'ستاره') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="53.5" rx="14" ry="4"/>
                    <path d="M32 9l6.2 12.6 13.8 2-10 9.7 2.4 13.7L32 40.6l-12.4 6.5 2.4-13.7-10-9.7 13.8-2Z" fill="url(#i3ds-body)"/>
                    <path d="M32 9l6.2 12.6 4.2.6" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" opacity=".6"/>
                    <path d="M47 11l1.7 3.9 3.9 1.7-3.9 1.7L47 22.2l-1.7-3.9-3.9-1.7 3.9-1.7Z" fill="url(#i3ds-accent)"/>
                    <circle cx="16.5" cy="45" r="2.2" fill="url(#i3ds-accent)"/>
                </svg>
                <span class="i3ds-name">{{ $say('Star', 'ستاره') }}</span>
            </div>
        </div>

        <p class="i3ds-meta">
            {{ $say($num('12') . ' icons · 100% inline SVG · zero external assets', $num('12') . ' آیکون · ۱۰۰٪ SVG درون‌خطی · بدون هیچ فایل بیرونی') }}
        </p>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div class="i3ds-head">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('One language, every size', 'یک زبان، هر اندازه') }}</h3>
            <p>
                {{ $say('The same gradients hold up from a 22px inline button icon to a 108px display piece — stroke-free geometry and a shared gloss keep the edges clean at any scale.', 'همین گرادیان‌ها از آیکون ۲۲ پیکسلیِ داخل دکمه تا قطعهٔ نمایشی ۱۰۸ پیکسلی سرپا می‌مانند — هندسهٔ بی‌خط و یک براق مشترک، لبه‌ها را در هر مقیاس تمیز نگه می‌دارند.') }}
            </p>
        </div>
        <div class="i3ds-sizes">
            <div class="i3ds-size">
                <button type="button" class="i3ds-chip">
                    <svg viewBox="0 0 64 64" aria-hidden="true">
                        <circle cx="32" cy="32" r="14" fill="url(#i3ds-body)"/>
                        <circle cx="32" cy="32" r="5.5" fill="url(#i3ds-accent)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" fill="url(#i3ds-body)"/>
                        <rect x="28.9" y="9" width="6.2" height="12" rx="3" transform="rotate(90 32 32)" fill="url(#i3ds-body)"/>
                    </svg>
                    {{ $say('Workspace settings', 'تنظیمات ورک‌اسپیس') }}
                </button>
                <small>{{ $say('22px, in a button', '۲۲ پیکسل، داخل دکمه') }}</small>
            </div>
            <div class="i3ds-size">
                <span class="i3ds-field">
                    <svg viewBox="0 0 64 64" aria-hidden="true">
                        <path d="M39.5 39.5 52 52" stroke="url(#i3ds-accent)" stroke-width="7.5" stroke-linecap="round"/>
                        <circle cx="27.5" cy="27.5" r="16.5" fill="url(#i3ds-body)"/>
                        <circle cx="27.5" cy="27.5" r="9.5" fill="url(#i3ds-accent)"/>
                        <path d="M16.2 19.7a14.5 14.5 0 0 1 9.3-6.3" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" opacity=".6"/>
                    </svg>
                    {{ $say('Search Northlight docs…', 'جست‌وجو در مستندات Northlight…') }}
                </span>
                <small>{{ $say('24px, in a field', '۲۴ پیکسل، داخل فیلد') }}</small>
            </div>
            <div class="i3ds-size">
                <span class="i3ds-row">
                    <svg viewBox="0 0 64 64" aria-hidden="true">
                        <ellipse class="i3ds-sh" cx="32" cy="53.5" rx="17" ry="4"/>
                        <path d="M12 25a4 4 0 0 1 4-4h10.5l5 5H48a4 4 0 0 1 4 4v14a4 4 0 0 1-4 4H16a4 4 0 0 1-4-4Z" fill="url(#i3ds-accent)"/>
                        <rect x="12" y="31" width="40" height="4" rx="2" fill="#fff" opacity=".4"/>
                        <path d="M12 35h40v9a4 4 0 0 1-4 4H16a4 4 0 0 1-4-4Z" fill="url(#i3ds-body)"/>
                    </svg>
                    {{ $say('lighthouse-reports / ' . $num('2026'), 'lighthouse-reports / ' . $num('2026')) }}
                </span>
                <small>{{ $say('38px, in a row', '۳۸ پیکسل، داخل ردیف') }}</small>
            </div>
            <div class="i3ds-size i3ds-display">
                <svg viewBox="0 0 64 64" role="img" aria-label="{{ $say('Rocket, display size', 'موشک، اندازهٔ نمایشی') }}">
                    <ellipse class="i3ds-sh" cx="32" cy="55" rx="15" ry="4.5"/>
                    <path d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z" fill="url(#i3ds-accent)"/>
                    <path d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z" fill="url(#i3ds-accent)"/>
                    <path d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z" fill="url(#i3ds-accent)"/>
                    <path d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z" fill="url(#i3ds-body)"/>
                    <circle cx="32" cy="26" r="5.5" fill="url(#i3ds-accent)"/>
                    <circle cx="30.2" cy="24.2" r="1.6" fill="#fff" opacity=".8"/>
                    <ellipse cx="27.5" cy="20" rx="2.3" ry="6" transform="rotate(14 27.5 20)" fill="url(#i3ds-gloss)"/>
                </svg>
                <small>{{ $say('108px, display', '۱۰۸ پیکسل، نمایشی') }}</small>
            </div>
        </div>
    </section>
</div>
