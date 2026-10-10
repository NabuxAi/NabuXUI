{{--
    GitHub's emoji reaction bar on a real comment: toggling pills moves the
    CounterLabel, the + popover mints a fresh pill in place, and a variant
    strip shows compact, own-reaction and lone-add states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');
    .prr-root {
        --prr-canvas: #ffffff; --prr-subtle: #f6f8fa; --prr-fg: #1f2328; --prr-muted: #59636e;
        --prr-border: #d1d9e0; --prr-accent: #0969da; --prr-count: #eff2f5;
        font-family: 'Figtree', 'Inter', 'Vazirmatn', sans-serif;
        color: var(--prr-fg);
    }
    html[data-theme="dark"] .prr-root {
        --prr-canvas: #0d1117; --prr-subtle: #151b23; --prr-fg: #f0f6fc; --prr-muted: #9198a1;
        --prr-border: #3d444d; --prr-accent: #4493f8; --prr-count: #262c36;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .prr-root {
            --prr-canvas: #0d1117; --prr-subtle: #151b23; --prr-fg: #f0f6fc; --prr-muted: #9198a1;
            --prr-border: #3d444d; --prr-accent: #4493f8; --prr-count: #262c36;
        }
    }
    .prr-root :focus-visible { outline: 2px solid var(--prr-accent); outline-offset: 2px; border-radius: 6px; }

    .prr-comment { inline-size: min(100%, 40rem); margin-inline: auto; border: 1px solid var(--prr-border); border-radius: 6px; background: var(--prr-canvas); }
    .prr-comment > header { display: flex; flex-wrap: wrap; gap: .35rem .5rem; align-items: center; padding: .55rem .9rem; border-block-end: 1px solid var(--prr-border); background: var(--prr-subtle); border-start-start-radius: 6px; border-start-end-radius: 6px; font: 400 .75rem/1.4 inherit; }
    .prr-avatar { flex: none; inline-size: 1.4rem; aspect-ratio: 1; border-radius: 50%; display: grid; place-items: center; background: color-mix(in srgb, var(--prr-accent) 18%, var(--prr-canvas)); color: var(--prr-accent); font: 600 .7rem/1 inherit; }
    .prr-comment > header b { font-weight: 600; }
    .prr-comment > header time { color: var(--prr-muted); }
    .prr-comment > header .prr-ref { margin-inline-start: auto; color: var(--prr-muted); font: 500 .6875rem/1 inherit; border: 1px solid var(--prr-border); border-radius: 2em; padding: .15rem .5rem; }
    .prr-comment > p { margin: 0; padding: .85rem .9rem; font: 400 .875rem/1.65 inherit; }
    .prr-bar { position: relative; display: flex; flex-wrap: wrap; gap: .4rem; align-items: center; padding: .6rem .9rem .8rem; border-block-start: 1px solid var(--prr-border); }
    .prr-pill { display: inline-flex; align-items: center; gap: .45rem; block-size: 1.75rem; padding-inline: .55rem; border: 1px solid transparent; border-radius: 2em; background: none; font: 500 .8125rem/1 inherit; cursor: pointer; }
    .prr-pill:hover { border-color: var(--prr-border); }
    .prr-count { display: inline-grid; place-items: center; min-inline-size: 1.25rem; padding-inline: .4rem; block-size: 1.25rem; border-radius: 2em; background: var(--prr-count); font: 500 .75rem/1 inherit; }
    .prr-pill[aria-pressed="true"] { border-color: var(--prr-accent); background: color-mix(in srgb, var(--prr-accent) 7%, var(--prr-canvas)); }
    .prr-pill[aria-pressed="true"] .prr-count { background: color-mix(in srgb, var(--prr-accent) 15%, var(--prr-canvas)); color: var(--prr-accent); }
    .prr-add { position: relative; }
    .prr-add > button { display: inline-grid; place-items: center; inline-size: 1.75rem; aspect-ratio: 1; padding: 0; border: 1px solid var(--prr-border); border-radius: 2em; background: none; color: var(--prr-muted); cursor: pointer; font: 400 .75rem/1 inherit; }
    .prr-add > button:hover { border-color: var(--prr-accent); color: var(--prr-accent); }
    .prr-picker { position: absolute; inset-block-start: calc(100% + .4rem); inset-inline-start: 0; z-index: 5; display: grid; grid-template-columns: repeat(4, 1fr); gap: .15rem; padding: .35rem; border: 1px solid var(--prr-border); border-radius: 6px; background: var(--prr-canvas); box-shadow: 0 8px 24px rgba(140, 149, 159, .2); }
    .prr-picker button { inline-size: 2rem; aspect-ratio: 1; display: grid; place-items: center; border: 0; border-radius: 6px; background: none; font-size: 1.05rem; cursor: pointer; }
    .prr-picker button:hover { background: var(--prr-subtle); }
    .prr-strip { display: flex; flex-wrap: wrap; gap: 1.25rem 2.25rem; align-items: center; justify-content: center; }
    .prr-spec { display: grid; gap: .45rem; justify-items: center; }
    .prr-spec small { font: 400 .6875rem/1.4 inherit; color: var(--nx-text-muted); }
    .prr-sm.prr-pill { block-size: 1.5rem; font-size: .75rem; }
    .prr-sm .prr-count { block-size: 1.1rem; font-size: .6875rem; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.prr-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .prr-picker { inset-inline-start: auto; inset-inline-end: 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .prr-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="prr-root" x-data="{
        open: false,
        rx: [
            { e: '🎉', n: 12, mine: true },
            { e: '🚀', n: 8, mine: false },
            { e: '❤️', n: 3, mine: false },
            { e: '👀', n: 1, mine: false },
        ],
        tray: ['👍', '👎', '😄', '🎉', '😕', '❤️', '🚀', '👀'],
        toggle(r) { r.mine = !r.mine; r.n += r.mine ? 1 : -1 },
        pick(e) {
            const hit = this.rx.find((r) => r.e === e);
            if (hit) { hit.n += 1; hit.mine = true } else { this.rx.push({ e, n: 1, mine: true }) }
            this.open = false;
        },
        rtl: document.documentElement.dir === 'rtl',
        num(n) { return this.rtl ? String(n).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
    }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Feedback in one tap, no words needed', 'فیدبک با یک لمس، بی‌خودِ کلمه') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('Tap a pill to join the crowd — your ring turns blue and the counter moves. The + opens the tray; whatever you pick becomes a brand-new pill.', 'روی قرص‌ها بزنید تا به جمع بپیوندید — حلقهٔ شما آبی می‌شود و شمارنده جابه‌جا می‌شود. علامت + سینی را باز می‌کند؛ هرچه بردارید قرص تازه‌ای می‌شود.') }}
            </p>
        </div>

        <div class="prr-comment">
            <header>
                <span class="prr-avatar" aria-hidden="true">{{ $say('M', 'م') }}</span>
                <b>{{ $say('Milad', 'میلاد') }}</b>
                <time>{{ $say('commented 2 hours ago', '۲ ساعت پیش کامنت گذاشت') }}</time>
                <span class="prr-ref">{{ $say('release 2.4', 'نسخهٔ ۲٫۴') }}</span>
            </header>
            <p>{{ $say('Shipping this Friday: the PDF export goes live for every workspace, and the report queue clears in under a minute. Chaos-proof your Friday plans accordingly. 🗓️', 'این جمعه منتشر می‌شود: خروجی PDF برای همهٔ ورک‌اسپیس‌ها فعال می‌شود و صف گزارش‌ها در کمتر از یک دقیقه خالی می‌شود. برنامهٔ جمعه‌تان را بر همین اساس محکم کنید. 🗓️') }}</p>
            <div class="prr-bar">
                <template x-for="(r, i) in rx" :key="r.e">
                    <button type="button" class="prr-pill" x-show="r.n > 0" :aria-pressed="r.mine" x-on:click="toggle(r)"
                        :aria-label="(rtl ? num(r.n) + ' واکنش ' : r.n + ' reactions with ') + r.e">
                        <span aria-hidden="true" x-text="r.e"></span>
                        <span class="prr-count" x-text="num(r.n)"></span>
                    </button>
                </template>
                <div class="prr-add">
                    <button type="button" x-on:click="open = !open" :aria-expanded="open" aria-label="{{ $say('Add reaction', 'افزودن واکنش') }}">🙂⁺</button>
                    <div class="prr-picker" x-show="open" x-cloak x-on:click.outside="open = false" role="menu" aria-label="{{ $say('Pick a reaction', 'انتخاب واکنش') }}">
                        <template x-for="e in tray" :key="e">
                            <button type="button" role="menuitem" x-on:click="pick(e)" :aria-label="e" x-text="e"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The same bar, three postures', 'همان نوار، سه حالت‌وشکل') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Compact in list rows, yours with the blue ring, and the lone add-button before anyone dares to react.', 'کوچک در ردیف فهرست‌ها، مالِ شما با حلقهٔ آبی، و دکمهٔ تنها پیش از آنکه کسی جرئت واکنش پیدا کند.') }}
        </p>
    </div>
    <div class="prr-root prr-strip">
        <div class="prr-spec">
            <span class="prr-pill prr-sm" aria-pressed="false"><span aria-hidden="true">🚀</span><span class="prr-count">{{ $say('23', '۲۳') }}</span></span>
            <small>compact</small>
        </div>
        <div class="prr-spec">
            <span class="prr-pill" aria-pressed="true"><span aria-hidden="true">🎉</span><span class="prr-count">{{ $fa ? '۱۳' : '13' }}</span></span>
            <small>aria-pressed="true"</small>
        </div>
        <div class="prr-spec">
            <span class="prr-pill" aria-pressed="false"><span aria-hidden="true">😃</span><span class="prr-count">{{ $fa ? '۵' : '5' }}</span></span>
            <small>neutral</small>
        </div>
        <div class="prr-spec">
            <span class="prr-pill" aria-pressed="false"><span aria-hidden="true">👀</span><span class="prr-count">{{ $fa ? '۱' : '1' }}</span></span>
            <small>counter</small>
        </div>
        <div class="prr-spec">
            <span class="prr-add"><button type="button" aria-label="{{ $say('Add reaction', 'افزودن واکنش') }}">🙂⁺</button></span>
            <small>add</small>
        </div>
    </div>
</section>
