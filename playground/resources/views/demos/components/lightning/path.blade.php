{{--
    The Lightning Path on a real opportunity: eight sales stages painted in
    the Marketing Cloud palette, chevrons notched in the reading direction,
    the current stage carrying its coaching «next step» field, the always-on
    Mark-Stage-Complete CTA advancing the bar, and the escape hatch that
    drops the deal to Closed Lost. A second specimen shows the four
    statuses side by side.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Stage ladder: fa, en, marketing colour
    $stages = [
        ['کشف فرصت', 'Prospecting', '#0176D3'],
        ['ارزیابی صلاحیت', 'Qualification', '#0B827C'],
        ['تحلیل نیاز', 'Needs Analysis', '#6739B7'],
        ['ارزش پیشنهادی', 'Value Proposition', '#FE9339'],
        ['شناسایی تصمیم‌گیرنده', 'Id. Decision Makers', '#B32D69'],
        ['پیشنهاد و قیمت', 'Proposal/Price Quote', '#FFB75D'],
        ['مذاکره و بازبینی', 'Negotiation/Review', '#BA0517'],
        ['بسته‌شده-موفق', 'Closed Won', '#04844B'],
    ];

    $stepsJson = json_encode(array_map(static fn (array $s): array => ['fa' => $s[0], 'en' => $s[1], 'color' => $s[2]], $stages), JSON_UNESCAPED_UNICODE);
@endphp
<style>
    .slp-root {
        --slp-blue: #0176D3; --slp-teal: #0B827C; --slp-purple: #6739B7; --slp-orange: #FE9339;
        --slp-pink: #B32D69; --slp-yellow: #FFB75D; --slp-red: #BA0517; --slp-green: #04844B;
        --slp-text: #181818; --slp-weak: #444444; --slp-muted: #706E6B;
        --slp-border: #DDDBDA; --slp-bg: #F3F3F3; --slp-card: #FFFFFF;
        --slp-step-bg: #E8E8E8; --slp-step-ink: #3E3E3C;
        font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--slp-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .slp-root {
        --slp-blue: #0D9DDA; --slp-teal: #12A39B; --slp-purple: #A57DE8; --slp-orange: #FE9339;
        --slp-pink: #E96BA8; --slp-yellow: #FFB75D; --slp-red: #FE5C4C; --slp-green: #0E9E5B;
        --slp-text: #F3F3F3; --slp-weak: #CECECE; --slp-muted: #A5A5A5;
        --slp-border: #474747; --slp-bg: #181818; --slp-card: #232323;
        --slp-step-bg: #3B3B3B; --slp-step-ink: #D5D5D5;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .slp-root {
            --slp-blue: #0D9DDA; --slp-teal: #12A39B; --slp-purple: #A57DE8; --slp-orange: #FE9339;
            --slp-pink: #E96BA8; --slp-yellow: #FFB75D; --slp-red: #FE5C4C; --slp-green: #0E9E5B;
            --slp-text: #F3F3F3; --slp-weak: #CECECE; --slp-muted: #A5A5A5;
            --slp-border: #474747; --slp-bg: #181818; --slp-card: #232323;
            --slp-step-bg: #3B3B3B; --slp-step-ink: #D5D5D5;
        }
    }
    .slp-root, .slp-root *, .slp-root *::before, .slp-root *::after { box-sizing: border-box; }
    .slp-root :focus-visible { outline: 2px solid var(--slp-blue); outline-offset: 2px; }

    .slp-card { inline-size: min(100%, 46rem); margin-inline: auto; padding: 1rem 1rem 1.25rem;
                border: 1px solid var(--slp-border); border-radius: .25rem; background: var(--slp-card);
                display: grid; gap: 1rem; }
    .slp-what { display: grid; gap: .15rem; }
    .slp-what small { font-size: .78rem; color: var(--slp-muted); }
    .slp-what b { font-size: 1.1rem; }
    .slp-track { margin-inline: calc(-1rem - 1px); overflow-x: auto; scrollbar-width: thin; }
    .slp-rail { display: flex; inline-size: max(100%, 47rem); }
    .slp-step { flex: 1; position: relative; display: grid; align-content: center; min-inline-size: 8.5rem;
                block-size: 2.6rem; padding-inline: 1.55rem; background: var(--slp-step-bg);
                color: var(--slp-step-ink); font-size: .78rem; font-weight: 600; line-height: 1.15;
                clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%, 12px 50%);
                border: 0; cursor: pointer; text-align: start;
                transition: background .15s ease, color .15s ease; }
    .slp-step:first-child { clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%); padding-inline-start: 1rem; }
    [dir="rtl"] .slp-step { clip-path: polygon(12px 0, 100% 0, calc(100% - 12px) 50%, 100% 100%, 12px 100%, 0 50%); }
    [dir="rtl"] .slp-step:first-child { clip-path: polygon(12px 0, 100% 0, calc(100% - 12px) 50%, 100% 100%, 12px 100%); padding-inline-start: 1.55rem; padding-inline-end: 1rem; }
    .slp-step[data-status="complete"] { background: var(--slp-stage); color: #FFFFFF; }
    .slp-step[data-status="current"] { background: var(--slp-stage); color: #FFFFFF; }
    .slp-step[data-status="current"]::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: .55rem;
                inline-size: 3px; background: #FFFFFF; }
    [dir="rtl"] .slp-step[data-status="current"]::before { inset-inline-start: auto; inset-inline-end: .55rem; }
    .slp-step[data-status="lost"] { background: var(--slp-red); color: #FFFFFF; }
    .slp-step[data-status="lost"]::before { content: '✕'; position: absolute; inset-block-start: .25rem; inset-inline-start: .5rem;
                font-size: .6rem; line-height: 1; }
    [dir="rtl"] .slp-step[data-status="lost"]::before { inset-inline-start: auto; inset-inline-end: .5rem; }
    .slp-step:hover:not([data-status="current"]):not([data-status="lost"]) { filter: brightness(.94); }
    html[data-theme="dark"] .slp-step:hover:not([data-status="current"]):not([data-status="lost"]),
    html:not([data-theme="light"]) .slp-step:hover:not([data-status="current"]):not([data-status="lost"]) { filter: brightness(1.14); }
    .slp-coach { display: grid; gap: .35rem; padding: .75rem; border: 1px solid var(--slp-border);
                 border-start-start-radius: .25rem; border-start-end-radius: .25rem;
                 border-block-start: 2px solid var(--slp-stage, var(--slp-blue)); }
    .slp-coach label { font-size: .72rem; font-weight: 700; color: var(--slp-muted); }
    .slp-coach input { inline-size: 100%; padding: .4rem .55rem; border: 1px solid var(--slp-border);
                 border-radius: .25rem; background: var(--slp-card); color: inherit;
                 font: inherit; font-size: .85rem; }
    .slp-coach input:focus-visible { outline: 2px solid var(--slp-blue); outline-offset: 0; }
    .slp-foot { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
    .slp-hint { font-size: .78rem; color: var(--slp-muted); }
    .slp-btn { padding: .45rem 1rem; border: 1px solid var(--slp-border); border-radius: .25rem;
               background: var(--slp-card); color: var(--slp-text); font: inherit; font-size: .85rem;
               cursor: pointer; transition: background .15s ease; }
    .slp-btn:hover { background: var(--slp-bg); }
    .slp-btn[data-variant="brand"] { background: var(--slp-blue); border-color: var(--slp-blue); color: #FFFFFF; font-weight: 600; }
    .slp-btn[data-variant="brand"]:hover { background: color-mix(in srgb, var(--slp-blue) 85%, #000); }
    .slp-btn[data-variant="lost"] { color: var(--slp-red); }
    .slp-flip { display: inline-flex; flex-wrap: wrap; align-items: center; gap: .4rem; font-size: .8rem; color: var(--slp-muted); margin-inline-start: auto; }
    .slp-won { display: flex; align-items: center; gap: .5rem; padding: .55rem .75rem; border-radius: .25rem;
               background: color-mix(in srgb, var(--slp-green) 12%, var(--slp-card)); color: var(--slp-green);
               font-size: .85rem; font-weight: 600; }

    .slp-spec { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; justify-content: center; align-items: flex-start; }
    .slp-spec-cell { display: grid; gap: .4rem; justify-items: center; inline-size: 9.5rem; }
    .slp-spec-cell > small { font-size: .72rem; color: var(--slp-muted); }
    .slp-spec-chip { display: flex; }
    .slp-spec-chip .slp-step { display: grid; align-content: center; min-inline-size: 6rem; inline-size: 9.5rem;
                padding-inline: 1.25rem; block-size: 2.4rem; cursor: default; }
    :where(.nx-js) .pg:has(.slp-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .slp-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="slp-root"
    x-data="{
        cur: 5,
        lost: false,
        steps: {{ $stepsJson }},
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        toFa(n) { return this.t === 'fa' ? String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
        name(i) { return this.t === 'fa' ? this.steps[i].fa : this.steps[i].en },
        status(i) {
            if (this.lost) return i < this.cur ? 'complete' : 'lost';
            return i < this.cur ? 'complete' : (i === this.cur ? 'current' : 'incomplete');
        },
        advance() { if (this.cur < this.steps.length - 1) this.cur++ },
        lose() { this.lost = true; this.cur = this.steps.length - 2 },
        revive() { this.lost = false; this.cur = 5 },
    }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One click per stage', 'هر مرحله، یک کلیک') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Mark Stage as Complete closes the current stage and fills its chevron; the escape hatch drops the deal to Closed Lost. Click any completed step to rewind.', '«تکمیل مرحله» مرحلهٔ جاری را می‌بندد و شِورانش را رنگ می‌کند؛ hatch فرار، معامله را به «بسته‌شده-ناموفق» می‌برد. برای برگشت روی هر مرحلهٔ کامل‌شده کلیک کنید.') }}
            </p>
        </div>

        <div class="slp-card">
            <div class="slp-what">
                <small>{{ $say('Opportunity · Acme Industrial', 'فرصت · صنایع آکمی') }}</small>
                <b>{{ $say('Nabu annual license — renewal', 'لایسنس سالانهٔ نابو — تمدید') }}</b>
            </div>

            <div class="slp-track">
                <ol class="slp-rail" style="margin: 0; padding: 0; list-style: none">
                    <template x-for="(s, i) in steps" :key="i">
                        <li style="display: contents">
                            <button type="button" class="slp-step" :style="'--slp-stage: ' + s.color"
                                :data-status="status(i)"
                                :aria-current="i === cur && !lost ? 'step' : null"
                                x-on:click="if (i < cur && !lost) cur = i">
                                <span x-text="name(i)"></span>
                            </button>
                        </li>
                    </template>
                </ol>
            </div>

            <template x-if="!lost">
                <div class="slp-coach" :style="'--slp-stage: ' + steps[cur].color">
                    <label for="slp-next">{{ $say('Next step', 'گام بعدی') }}</label>
                    <input id="slp-next" type="text" placeholder="{{ $say('e.g. send the revised quote', 'مثلاً ارسال پیش‌فاکتور بازبینی‌شده') }}">
                </div>
            </template>

            <div class="slp-foot">
                <button type="button" class="slp-btn" data-variant="brand"
                    x-show="!lost && cur < steps.length - 1"
                    x-on:click="advance()">
                    {{ $say('Mark Stage as Complete', 'تکمیل مرحله') }}
                </button>
                <span class="slp-won" x-show="cur === steps.length - 1 && !lost" x-cloak>
                    <x-nx::icon name="check-circle" /> {{ $say('Won — time to invoice', 'موفق — وقت صدور فاکتور') }}
                </span>
                <span class="slp-flip" x-show="!lost">
                    {{ $say('Deal fell through?', 'معامله لنگید؟') }}
                    <button type="button" class="slp-btn" data-variant="lost" x-on:click="lose()">{{ $say('Mark as Lost', 'علامت‌گذاری ناموفق') }}</button>
                </span>
                <span class="slp-flip" x-show="lost" x-cloak>
                    {{ $say('Reopen the deal:', 'بازگشایی معامله:') }}
                    <button type="button" class="slp-btn" x-on:click="revive()">{{ $say('Back to Proposal', 'بازگشت به پیشنهاد') }}</button>
                </span>
            </div>
            <p class="slp-hint" style="margin: 0">
                {{ $say('Stage ', 'مرحلهٔ ') }}<b style="color: var(--slp-text)" x-text="toFa(cur + 1)"></b>
                {{ $say('of 8 — ', 'از ۸ — ') }}<span x-text="name(cur)"></span>
            </p>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The four statuses', 'چهار وضعیت مرحله') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Complete takes its marketing colour, current adds the white edge, incomplete rests on grey, and the lost chevron turns red.', 'تکمیل‌شده رنگ مارکتینگ خودش را می‌گیرد، جاری خط سفید می‌گذارد، ناتمام روی خاکستری می‌ماند و شِورانِ ناموفق قرمز می‌شود.') }}
            </p>
        </div>
        <div class="slp-spec" style="inline-size: 100%">
            <div class="slp-spec-cell">
                <span class="slp-spec-chip"><span class="slp-step" data-status="complete" style="--slp-stage: var(--slp-teal)">{{ $say('Qualification', 'ارزیابی صلاحیت') }}</span></span>
                <small>complete</small>
            </div>
            <div class="slp-spec-cell">
                <span class="slp-spec-chip"><span class="slp-step" data-status="current" style="--slp-stage: var(--slp-purple)">{{ $say('Needs Analysis', 'تحلیل نیاز') }}</span></span>
                <small>current</small>
            </div>
            <div class="slp-spec-cell">
                <span class="slp-spec-chip"><span class="slp-step" data-status="incomplete" style="--slp-stage: var(--slp-orange)">{{ $say('Value Proposition', 'ارزش پیشنهادی') }}</span></span>
                <small>incomplete</small>
            </div>
            <div class="slp-spec-cell">
                <span class="slp-spec-chip"><span class="slp-step" data-status="lost">{{ $say('Closed Lost', 'بسته‌شده-ناموفق') }}</span></span>
                <small>lost</small>
            </div>
        </div>
    </section>
</div>
