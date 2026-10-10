{{--
    The Lightning Welcome Mat greeting a brand-new sales app: an illustrated
    info split beside the four-step checklist, each step ticking with its own
    counter, the mat flipping to the green all-set state, and the ✕
    dismissing the whole card. The specimen row shows split, compact and
    completed variants.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $jsf = fn (string $s): string => str_replace('"', '&quot;', json_encode($s, JSON_UNESCAPED_UNICODE));
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap');
    .slw-root {
        --slw-blue: #0176D3; --slw-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
        --slw-green: #04844B; --slw-navy: #0B5CAB;
        --slw-text: #181818; --slw-weak: #444444; --slw-muted: #706E6B;
        --slw-border: #DDDBDA; --slw-bg: #F3F3F3; --slw-card: #FFFFFF;
        font-family: 'Source Sans 3', 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        color: var(--slw-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .slw-root {
        --slw-blue: #0D9DDA; --slw-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
        --slw-green: #0E9E5B; --slw-navy: #0B5CAB;
        --slw-text: #F3F3F3; --slw-weak: #CECECE; --slw-muted: #A5A5A5;
        --slw-border: #474747; --slw-bg: #181818; --slw-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .slw-root {
            --slw-blue: #0D9DDA; --slw-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
            --slw-green: #0E9E5B; --slw-navy: #0B5CAB;
            --slw-text: #F3F3F3; --slw-weak: #CECECE; --slw-muted: #A5A5A5;
            --slw-border: #474747; --slw-bg: #181818; --slw-card: #232323;
        }
    }
    .slw-root, .slw-root *, .slw-root *::before, .slw-root *::after { box-sizing: border-box; }
    .slw-root :focus-visible { outline: 2px solid var(--slw-blue); outline-offset: 2px; }

    .slw-card { position: relative; inline-size: min(100%, 46rem); margin-inline: auto; display: grid;
                grid-template-columns: minmax(11rem, 1fr) minmax(0, 1.2fr);
                border: 1px solid var(--slw-border); border-radius: .25rem; background: var(--slw-card); overflow: clip; }
    @media (max-width: 540px) { .slw-card { grid-template-columns: 1fr; } }
    .slw-close { position: absolute; inset-block-start: .5rem; inset-inline-end: .5rem; z-index: 2; inline-size: 1.75rem;
                aspect-ratio: 1; display: grid; place-items: center; border: 0; border-radius: .25rem;
                background: transparent; color: var(--slw-muted); cursor: pointer; font-size: .95rem; }
    .slw-close:hover { background: color-mix(in srgb, #FFFFFF 22%, transparent); color: #FFFFFF; }
    .slw-info { position: relative; display: grid; align-content: end; gap: .4rem; min-block-size: 15rem; padding: 1.25rem 1.1rem;
                background: linear-gradient(160deg, #0B5CAB, #0176D3 62%, #0E9E5B 130%); color: #FFFFFF; }
    .slw-art { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%; pointer-events: none; }
    .slw-art i { position: absolute; border-radius: 50%; background: color-mix(in srgb, #FFFFFF 16%, transparent); }
    .slw-art i:nth-child(1) { inline-size: 5.5rem; aspect-ratio: 1; inset-block-start: 14%; inset-inline-start: 12%; }
    .slw-art i:nth-child(2) { inline-size: 2.2rem; aspect-ratio: 1; inset-block-start: 48%; inset-inline-start: 58%; opacity: .7; }
    .slw-art i:nth-child(3) { inline-size: 3.4rem; aspect-ratio: 1; inset-block-start: 8%; inset-inline-start: 66%; opacity: .5; }
    .slw-info h3 { position: relative; margin: 0; font-size: 1.15rem; font-weight: 700; }
    .slw-info p { position: relative; margin: 0; font-size: .84rem; opacity: .92; }
    .slw-play { position: relative; justify-self: start; display: inline-flex; align-items: center; gap: .45rem; margin-block-start: .5rem;
                padding: .45rem .95rem; border: 1px solid color-mix(in srgb, #FFFFFF 55%, transparent); border-radius: .25rem;
                background: color-mix(in srgb, #FFFFFF 16%, transparent); color: #FFFFFF; font: inherit; font-size: .82rem;
                font-weight: 600; cursor: pointer; transition: background .15s ease; }
    .slw-play:hover { background: color-mix(in srgb, #FFFFFF 28%, transparent); }
    .slw-steps { display: grid; gap: 0; align-content: start; padding: .35rem .9rem .9rem; }
    .slw-steps > div { display: flex; align-items: center; gap: .75rem; padding: .15rem 0; }
    .slw-check { position: relative; flex: none; inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%;
                border: 1px solid var(--slw-border); background: var(--slw-card); color: transparent;
                display: grid; place-items: center; cursor: pointer; transition: background .15s ease, border-color .15s ease, color .15s ease; }
    .slw-check .nx-icon { inline-size: .8em; block-size: .8em; }
    .slw-check[aria-pressed="true"] { background: var(--slw-green); border-color: var(--slw-green); color: #FFFFFF; }
    .slw-step-text { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit; font: inherit;
                font-size: .85rem; font-weight: 600; cursor: pointer; text-align: start; padding: .35rem 0; }
    .slw-step-text:hover { color: var(--slw-blue); }
    .slw-step-text small { display: block; font-weight: 400; font-size: .74rem; color: var(--slw-muted); }
    .slw-step-num { flex: none; font-size: .72rem; font-weight: 700; color: var(--slw-muted); }
    .slw-progress { display: flex; align-items: center; gap: .6rem; padding: .6rem .9rem .8rem; }
    .slw-meter { flex: 1; block-size: 4px; border-radius: 999px; background: var(--slw-border); overflow: clip; }
    .slw-meter i { display: block; block-size: 100%; border-radius: inherit; background: var(--slw-green);
                inline-size: calc(var(--slw-done, 0) / 4 * 100%); transition: inline-size .25s ease; }
    .slw-progress b { font-size: .78rem; color: var(--slw-muted); white-space: nowrap; }
    .slw-set { margin-inline: .9rem .9rem; margin-block-end: .9rem; display: flex; align-items: center; gap: .5rem;
                padding: .55rem .75rem; border-radius: .25rem; background: color-mix(in srgb, var(--slw-green) 12%, var(--slw-card));
                color: var(--slw-green); font-size: .8rem; font-weight: 700; }
    .slw-gone { display: grid; place-items: center; gap: .5rem; inline-size: min(100%, 46rem); margin-inline: auto;
                min-block-size: 9rem; border: 1px dashed var(--slw-border); border-radius: .25rem;
                color: var(--slw-muted); font-size: .85rem; text-align: center; padding: 1rem; }
    .slw-gone button { border: 0; background: transparent; color: var(--slw-blue); font: inherit; font-size: .82rem;
                font-weight: 600; cursor: pointer; text-decoration: underline; }

    .slw-spec { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; align-items: stretch; }
    .slw-spec-cell { display: grid; gap: .4rem; justify-items: center; }
    .slw-spec-cell > small { font-size: .72rem; color: var(--slw-muted); }
    .slw-mini { display: grid; grid-template-columns: 5rem 9rem; inline-size: 14rem; block-size: 7rem; border: 1px solid var(--slw-border);
                border-radius: .25rem; background: var(--slw-card); overflow: clip; }
    .slw-mini-side { background: linear-gradient(160deg, #0B5CAB, #0176D3); }
    .slw-mini[data-split="false"] .slw-mini-side { display: none; }
    .slw-mini-lines { padding: .7rem .7rem 0; display: grid; gap: .5rem; align-content: start; }
    .slw-mini-lines i { block-size: .4rem; border-radius: 999px; background: var(--slw-border); }
    .slw-mini-lines i:first-child { inline-size: 70%; background: color-mix(in srgb, currentColor 22%, var(--slw-border)); }
    .slw-mini[data-complete="true"] .slw-mini-lines i { background: color-mix(in srgb, var(--slw-green) 45%, var(--slw-border)); }
    :where(.nx-js) .pg:has(.slw-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .slw-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="slw-root"
    x-data="{
        gone: false,
        done: [true, true, false, false],
        labels: [
            { fa: 'پروفایل شرکت را کامل کنید', en: 'Complete your company profile', hint: 'لوگو، اندازه و حوزهٔ فعالیت', hintEn: 'Logo, size and industry' },
            { fa: 'همکارانتان را دعوت کنید', en: 'Invite your teammates', hint: 'حداقل دو نفر از تیم فروش', hintEn: 'At least two sales folks' },
            { fa: 'اولین فرصت را بسازید', en: 'Create your first opportunity', hint: 'مثلاً لایسنس سالانهٔ یک مشتری', hintEn: 'e.g. a customer’s annual license' },
            { fa: 'اپ موبایل را امتحان کنید', en: 'Try the mobile app', hint: 'همان داده‌ها، جیب شما', hintEn: 'Same data, your pocket' },
        ],
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        get count() { return this.done.filter(Boolean).length },
        get all() { return this.count === this.done.length },
        label(i) { return this.t === 'fa' ? this.labels[i].fa : this.labels[i].en },
        hint(i) { return this.t === 'fa' ? this.labels[i].hint : this.labels[i].hintEn },
        num(n) { return this.t === 'fa' ? String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
        tick(i) { this.done[i] = !this.done[i] },
    }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The first hello of a new app', 'اولین سلامِ یک اپ تازه') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Tick the two remaining steps and the mat flips to its green all-set state; the ✕ dismisses it and leaves a way back.', 'دو مرحلهٔ باقی‌مانده را تیک بزنید تا مات به حالت سبز «همه‌چیز آماده» برسد؛ ✕ آن را می‌بندد و راه برگشت باقی می‌گذارد.') }}
            </p>
        </div>

        <template x-if="!gone">
            <section class="slw-card" aria-label="{{ $say('Welcome mat', 'مات خوش‌آمد') }}">
                <button type="button" class="slw-close" aria-label="{{ $say('Dismiss the welcome mat', 'بستن مات خوش‌آمد') }}" x-on:click="gone = true">✕</button>
                <figure class="slw-info" style="margin: 0">
                    <span class="slw-art" aria-hidden="true"><i></i><i></i><i></i></span>
                    <h3>{{ $say('Welcome to Nabu Sales', 'به فروش نابو خوش آمدید') }}</h3>
                    <p>{{ $say('Four small steps and your team is selling — the tour takes about three minutes.', 'چهار قدم کوچک و تیم شما وارد فروش می‌شود — کل تور حدود سه دقیقه است.') }}</p>
                    <button type="button" class="slw-play"><x-nx::icon name="play" /> {{ $say('Watch the tour', 'تماشای تور') }}</button>
                </figure>
                <div>
                    <ol class="slw-steps" style="margin: 0; list-style: none">
                        <template x-for="(d, i) in done" :key="i">
                            <div>
                                <button type="button" class="slw-check" :aria-pressed="d"
                                    :aria-label="(d ? {!! $jsf('انجام‌شده: ') !!} : {!! $jsf('انجام دادن: ') !!}) + label(i)"
                                    x-on:click="tick(i)">
                                    <x-nx::icon name="check" />
                                </button>
                                <button type="button" class="slw-step-text" x-on:click="tick(i)">
                                    <span x-text="label(i)"></span>
                                    <small x-text="hint(i)"></small>
                                </button>
                                <span class="slw-step-num" aria-hidden="true" x-text="num(i + 1)"></span>
                            </div>
                        </template>
                    </ol>
                    <div class="slw-progress">
                        <div class="slw-meter" role="progressbar" :aria-valuenow="count" :aria-valuemin="0" :aria-valuemax="4"
                            :style="'--slw-done: ' + count"><i></i></div>
                        <b x-text="num(count) + ' ' + {!! $jsf($say('of 4 completed', 'از ۴ تکمیل شد')) !!}"></b>
                    </div>
                    <p class="slw-set" x-show="all" x-cloak style="margin-block-start: 0">
                        <x-nx::icon name="check-circle" /> {{ $say('All set — happy selling!', 'همه‌چیز آماده — فروش خوبی داشته باشید!') }}
                    </p>
                </div>
            </section>
        </template>

        <template x-if="gone">
            <div class="slw-gone">
                <span>{{ $say('The mat is dismissed for good.', 'مات برای همیشه بسته شد.') }}</span>
                <button type="button" x-on:click="gone = false">{{ $say('Show it again', 'نمایش دوباره') }}</button>
            </div>
        </template>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The three faces of the mat', 'سه چهرهٔ مات') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('With or without the illustration split, and the completed state that fades its checklist to green.', 'با یا بدون ستون تصویر، و حالت تکمیل‌شده که چک‌لیستش سبز می‌شود.') }}
            </p>
        </div>
        <div class="slw-spec" style="inline-size: 100%">
            <div class="slw-spec-cell">
                <span class="slw-mini" data-split="true" aria-hidden="true"><span class="slw-mini-side"></span><span class="slw-mini-lines"><i></i><i></i><i></i></span></span>
                <small>split</small>
            </div>
            <div class="slw-spec-cell">
                <span class="slw-mini" data-split="false" aria-hidden="true"><span class="slw-mini-side"></span><span class="slw-mini-lines"><i></i><i></i><i></i></span></span>
                <small>no-info-split</small>
            </div>
            <div class="slw-spec-cell">
                <span class="slw-mini" data-split="true" data-complete="true" aria-hidden="true"><span class="slw-mini-side" style="background: linear-gradient(160deg, #04844B, #0E9E5B)"></span><span class="slw-mini-lines"><i></i><i></i><i></i></span></span>
                <small>complete</small>
            </div>
        </div>
    </section>
</div>
