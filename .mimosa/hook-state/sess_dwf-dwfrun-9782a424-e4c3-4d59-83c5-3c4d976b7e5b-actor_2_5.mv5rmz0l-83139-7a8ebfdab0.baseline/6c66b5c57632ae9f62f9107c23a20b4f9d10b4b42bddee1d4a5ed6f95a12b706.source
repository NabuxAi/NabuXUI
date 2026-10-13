{{--
    split-hover's real scenarios: a streetwear drop card whose Latin headline
    splits letter by letter — the glyph under the cursor jumps, spins or fills
    while its neighbours nudge half a step — and the Persian sibling card that
    moves word by word so the letter joining never tears. One mode switch
    drives both lines.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $letters = str_split('SUMMER DROP');
    $words = preg_split('/ /u', 'کالکشن تابستان از راه رسید') ?: [];
@endphp

<style>
    .spl-root {
        --spl-bg: #ffffff; --spl-ink: #1c1917; --spl-muted: #78716c;
        --spl-border: #e7e5e4; --spl-card: #fafaf9; --spl-accent: #7c3aed;
        --spl-cyan: #0891b2; --spl-amber: #b45309;
        display: grid; gap: 2rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--spl-ink);
    }
    html[data-theme="dark"] .spl-root {
        --spl-bg: #171514; --spl-ink: #fafaf9; --spl-muted: #a8a29e;
        --spl-border: #292524; --spl-card: #1c1917; --spl-accent: #a78bfa;
        --spl-cyan: #22d3ee; --spl-amber: #fbbf24;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .spl-root {
            --spl-bg: #171514; --spl-ink: #fafaf9; --spl-muted: #a8a29e;
            --spl-border: #292524; --spl-card: #1c1917; --spl-accent: #a78bfa;
            --spl-cyan: #22d3ee; --spl-amber: #fbbf24;
        }
    }
    .spl-drop {
        display: grid; gap: 1.1rem; justify-items: center; text-align: center;
        padding: clamp(1.5rem, 4vw, 2.75rem) 1.25rem;
        border: 1px solid var(--spl-border); border-radius: 1.25rem;
        background:
            radial-gradient(70% 100% at 50% 100%, color-mix(in oklab, var(--spl-accent) 8%, transparent), transparent 75%),
            var(--spl-bg);
        overflow: clip;
    }
    .spl-kicker {
        margin: 0; padding: .3rem .85rem; border: 1px solid var(--spl-border);
        border-radius: 999px; font-size: .74rem; font-weight: 600; color: var(--spl-muted);
    }
    .spl-line {
        margin: 0; font-weight: 800; letter-spacing: .01em;
        font-size: clamp(2rem, 9vw, 3.9rem); line-height: 1.3;
        overflow-wrap: anywhere;
    }
    .spl-l, .spl-w {
        display: inline-block;
        transition: translate .28s cubic-bezier(.3, 1.6, .4, 1),
                    rotate .5s cubic-bezier(.3, .7, .2, 1),
                    background-color .25s ease, color .25s ease;
    }
    .spl-gap { display: inline-block; inline-size: .38em; }
    .spl[data-mode='jump'] .spl-l:hover, .spl[data-mode='jump'] .spl-w:hover { translate: 0 -.45em; color: var(--spl-accent); }
    .spl[data-mode='spin'] .spl-l:hover, .spl[data-mode='spin'] .spl-w:hover { rotate: 360deg; color: var(--spl-cyan); }
    .spl[data-mode='fill'] .spl-l:hover, .spl[data-mode='fill'] .spl-w:hover {
        background: var(--spl-accent); color: var(--spl-bg); border-radius: .18em; padding-inline: .08em;
    }
    .spl[data-mode='jump'] .spl-l:hover + .spl-l,
    .spl[data-mode='jump'] .spl-l:has(+ .spl-l:hover) { translate: 0 -.16em; }
    .spl[data-mode='jump'] .spl-w:hover + .spl-w,
    .spl[data-mode='jump'] .spl-w:has(+ .spl-w:hover) { translate: 0 -.14em; color: var(--spl-amber); }
    .spl-meta { display: flex; flex-wrap: wrap; justify-content: center; gap: .4rem 1rem; margin: 0; font-size: .78rem; color: var(--spl-muted); }
    .spl-modes { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; }
    .spl-mode {
        padding: .42rem 1rem; border: 1px solid var(--spl-border); border-radius: 999px;
        background: var(--spl-card); color: var(--spl-ink); font: 600 .78rem/1.4 inherit;
        cursor: pointer; transition: border-color .2s ease, box-shadow .2s ease;
    }
    .spl-mode:hover { border-color: var(--spl-accent); }
    .spl-mode:focus-visible { outline: 2px solid var(--spl-accent); outline-offset: 2px; }
    .spl-mode[aria-pressed='true'] { border-color: var(--spl-accent); box-shadow: 0 0 0 1px var(--spl-accent); color: var(--spl-accent); }
    .spl-cta {
        padding: .55rem 1.35rem; border: none; border-radius: 999px;
        background: var(--spl-ink); color: var(--spl-bg); font: 700 .8rem/1.4 inherit;
        cursor: pointer; transition: translate .2s ease, opacity .2s ease;
    }
    .spl-cta:hover { translate: 0 -2px; }
    .spl-cta:focus-visible { outline: 2px solid var(--spl-accent); outline-offset: 2px; }
    .spl-note {
        margin: 0; font-size: .76rem; color: var(--spl-muted);
        padding: .55rem .95rem; border: 1px dashed var(--spl-border); border-radius: .85rem;
    }
    @media (prefers-reduced-motion: reduce) {
        .spl-l, .spl-w { transition: background-color .25s ease, color .25s ease; }
        .spl-cta, .spl-mode { transition: none; }
        .spl-cta:hover { translate: none; }
    }
</style>

<div class="spl-root">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Glyphs that answer the cursor', 'حروفی که به موس جواب می‌دهند') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The drop headline splits per letter for Latin; the glyph under your cursor acts while its two neighbours rise half a step, so a wave trails the pointer. Switch the behaviour — jump, spin, fill — and both lines obey.', 'تیترِ دراپ برای لاتین حرف‌به‌حرف باز می‌شود؛ حرف زیر موس نقش‌اش را اجرا می‌کند و دو همسایه‌اش نیم‌قدم بالا می‌آیند تا موجی دنبال اشاره‌گر بگردد. رفتار را عوض کنید — پرش، چرخش، پُرشدن — هر دو خط اطاعت می‌کنند.') }}
            </p>
        </div>

        <div class="spl"
            x-data="{
                mode: 'jump',
                saved: false,
                on: @js($say('You are on the list', 'در فهرست هستی')),
                off: @js($say('Notify me', 'به من خبر بده')),
            }"
            x-bind:data-mode="mode">
            <div class="spl-modes" role="group" aria-label="{{ $say('Letter behaviour', 'رفتار حروف') }}">
                @foreach (['jump' => ['Jump', 'پرش'], 'spin' => ['Spin', 'چرخش'], 'fill' => ['Fill', 'پُرشدن']] as $key => [$en, $faName])
                    <button type="button" class="spl-mode" x-bind:aria-pressed="mode === '{{ $key }}' ? 'true' : 'false'"
                            x-on:click="mode = '{{ $key }}'">{{ $say($en, $faName) }}</button>
                @endforeach
            </div>

            <div class="spl-drop">
                <p class="spl-kicker">{{ $say('Northwind Studio · flagship Rotterdam', 'استودیو نورث‌ویند · فلگ‌شیپ روتردام') }}</p>
                <h4 class="spl-line" dir="ltr" lang="en" aria-label="SUMMER DROP">
                    @foreach ($letters as $i => $ch)
                        @if ($ch === ' ')
                            <span class="spl-gap" aria-hidden="true"></span>
                        @else
                            <span class="spl-l" style="--i: {{ $i }}" aria-hidden="true">{{ $ch }}</span>
                        @endif
                    @endforeach
                </h4>
                <p class="spl-meta">
                    <span>{{ $say('June 21 · 09:00 CEST', '۲۱ ژوئن · ۰۹:۰۰ به‌وقیت اروپای مرکزی') }}</span>
                    <span>{{ $say('Limited to 300 pieces', 'محدود به ۳۰۰ قطعه') }}</span>
                </p>
                <button type="button" class="spl-cta" x-on:click="saved = true" x-text="saved ? on : off"></button>
            </div>

            <div class="spl-drop" dir="rtl" lang="fa">
                <p class="spl-kicker">نسخهٔ فارسی · واژه‌به‌واژه</p>
                <p class="spl-line" aria-label="کالکشن تابستان از راه رسید">
                    @foreach ($words as $i => $w)
                        <span class="spl-w" style="--i: {{ $i }}" aria-hidden="true">{{ $w }}</span>@if (!$loop->last)<span class="spl-gap" aria-hidden="true"></span>@endif
                    @endforeach
                </p>
                <p class="spl-note">
                    {{ $say('Persian is never split by letter — the joins would tear. The same three behaviours act on whole words instead.', 'فارسی هرگز حرف‌به‌حرف نمی‌شکند — اتصال حروف پاره می‌شد. همان سه رفتار به‌جای حرف، روی کل واژه اجرا می‌شود.') }}
                </p>
            </div>
        </div>
    </section>
</div>
