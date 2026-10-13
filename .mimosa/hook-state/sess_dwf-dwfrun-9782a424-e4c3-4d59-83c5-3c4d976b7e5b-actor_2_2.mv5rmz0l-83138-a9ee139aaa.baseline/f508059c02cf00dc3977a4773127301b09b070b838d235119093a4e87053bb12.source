{{--
    ripple-click's real scenario: the Atlas Studio invoice card — a filled
    button, a ghost button, a tab row and the card itself are all ripple
    surfaces. The ripple is born at the exact pointerdown coordinates (or the
    surface centre for Enter/Space), its radius is measured to the farthest
    corner so it always covers the whole surface, and the ink is currentColor
    so each surface ripples in its own colour. One AbortController wires —
    and unwires — every listener; leftover ink nodes are removed on destroy.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .mfrp-root {
        --mfrp-fg: #1c1917; --mfrp-muted: #78716c; --mfrp-border: #e7e5e4;
        --mfrp-bg: #ffffff; --mfrp-soft: #f5f5f4; --mfrp-accent: #4f46e5;
        display: grid; gap: 1rem; justify-items: start;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        color: var(--mfrp-fg);
    }
    html[data-theme="dark"] .mfrp-root {
        --mfrp-fg: #fafaf9; --mfrp-muted: #a8a29e; --mfrp-border: #292524;
        --mfrp-bg: #1c1917; --mfrp-soft: #292524; --mfrp-accent: #a5b4fc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfrp-root {
            --mfrp-fg: #fafaf9; --mfrp-muted: #a8a29e; --mfrp-border: #292524;
            --mfrp-bg: #1c1917; --mfrp-soft: #292524; --mfrp-accent: #a5b4fc;
        }
    }
    .mfrp-card {
        position: relative; overflow: clip; display: grid; gap: .9rem;
        inline-size: min(100%, 26rem); padding: 1.4rem;
        border: 1px solid var(--mfrp-border); border-radius: 1.1rem;
        background: var(--mfrp-bg); box-shadow: 0 18px 40px -32px rgb(0 0 0 / .4);
        cursor: pointer; -webkit-tap-highlight-color: transparent;
    }
    .mfrp-head { display: flex; align-items: baseline; gap: .75rem; flex-wrap: wrap; }
    .mfrp-card h3 { margin: 0; font-size: 1rem; }
    .mfrp-total { margin-inline-start: auto; font-variant-numeric: tabular-nums;
                  font-weight: 700; color: var(--mfrp-accent); }
    .mfrp-surf { position: relative; overflow: clip; -webkit-tap-highlight-color: transparent; }
    .mfrp-ink {
        position: absolute; border-radius: 50%; background: currentColor;
        translate: -50% -50%; pointer-events: none; opacity: 0;
        animation: mfrp-grow .55s cubic-bezier(.2, 0, 0, 1) forwards;
    }
    @keyframes mfrp-grow { from { scale: 0; opacity: .3; } to { scale: 1; opacity: 0; } }
    .mfrp-tabs {
        display: inline-flex; gap: .25rem; padding: .25rem;
        border: 1px solid var(--mfrp-border); border-radius: 999px;
        background: var(--mfrp-soft); max-inline-size: 100%; overflow-x: auto;
        scrollbar-width: none;
    }
    .mfrp-tabs::-webkit-scrollbar { display: none; }
    .mfrp-tab {
        position: relative; overflow: clip; padding: .5rem 1rem; border: none;
        border-radius: 999px; background: transparent; color: var(--mfrp-muted);
        font-family: inherit; font-size: .82rem; font-weight: 600; cursor: pointer;
        transition: background-color .2s ease, color .2s ease;
    }
    .mfrp-tab:hover { color: var(--mfrp-fg); }
    .mfrp-tab[aria-selected="true"] { background: var(--mfrp-accent); color: var(--mfrp-bg); }
    html[data-theme="dark"] .mfrp-tab[aria-selected="true"] { color: #1c1917; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfrp-tab[aria-selected="true"] { color: #1c1917; }
    }
    .mfrp-tab:focus-visible { outline: 2px solid var(--mfrp-accent); outline-offset: 1px; }
    .mfrp-panel {
        margin: 0; min-block-size: 3.2rem; font-size: .84rem;
        line-height: 1.7; color: var(--mfrp-muted);
    }
    .mfrp-actions { display: flex; flex-wrap: wrap; gap: .6rem; }
    .mfrp-primary {
        position: relative; overflow: clip; display: inline-flex; align-items: center; gap: .45rem;
        block-size: 40px; padding-inline: 1.1rem; border: none; border-radius: 999px;
        background: var(--mfrp-accent); color: var(--mfrp-bg); cursor: pointer;
        font-family: inherit; font-size: .84rem; font-weight: 600;
        transition: filter .15s ease;
    }
    html[data-theme="dark"] .mfrp-primary { color: #1c1917; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfrp-primary { color: #1c1917; }
    }
    .mfrp-primary:hover { filter: brightness(1.08); }
    .mfrp-primary svg, .mfrp-ghost svg {
        inline-size: .95rem; block-size: .95rem; stroke: currentColor; stroke-width: 2;
        fill: none; stroke-linecap: round; stroke-linejoin: round;
    }
    .mfrp-ghost {
        position: relative; overflow: clip; display: inline-flex; align-items: center; gap: .45rem;
        block-size: 40px; padding-inline: 1.1rem; border-radius: 999px;
        border: 1px solid var(--mfrp-border); background: var(--mfrp-bg);
        color: var(--mfrp-fg); cursor: pointer;
        font-family: inherit; font-size: .84rem; font-weight: 600;
        transition: border-color .15s ease;
    }
    .mfrp-ghost:hover { border-color: var(--mfrp-accent); }
    .mfrp-primary:focus-visible, .mfrp-ghost:focus-visible {
        outline: 2px solid var(--mfrp-accent); outline-offset: 2px;
    }
    .mfrp-hint {
        margin: 0; font-family: ui-monospace, monospace; font-size: .72rem;
        color: var(--mfrp-muted); font-variant-numeric: tabular-nums;
    }
    @media (prefers-reduced-motion: reduce) {
        .mfrp-ink { animation: none; opacity: 0; }
        .mfrp-tab, .mfrp-primary { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A ripple that knows where you pressed', 'موجی که می‌داند کجا فشار دادید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Every surface here — the card, both buttons and each tab — spawns its ripple from the exact pointerdown coordinates, sized to twice the farthest corner so it always sweeps the whole surface before fading. The ink is currentColor, so the filled button ripples light and the tabs ripple in the theme colour; Enter and Space drop it from the centre. One AbortController wires every listener and unwires them all at once.', 'هر سطح اینجا — کارت، هر دو دکمه و هر تب — موجش را از مختصات دقیق pointerdown می‌سازد و شعاعش را دو برابرِ دورترین گوشه می‌گیرد تا همیشه کل سطح را جارو کند و بعد محو شود. جوهر currentColor است، پس دکمهٔ پر با روشنی موج می‌گیرد و تب‌ها با رنگ تم؛ Enter و Space موج را از مرکز می‌اندازند. یک AbortController همه شنونده‌ها را سیم‌کشی و یک‌جا باز‌سیم‌کشی می‌کند.') }}
        </p>
    </div>

    <div class="mfrp-root"
        x-data="{
            ac: null, mq: null, inks: [], tab: 'overview', last: '',
            idle: @js($say('no ripple yet — press the card, a tab or a button; or Tab + Enter to drop one from the centre', 'هنوز موجی نیفتاده — کارت، تب یا دکمه را فشار دهید؛ یا با Tab و Enter موجی از مرکز بیندازید')),
            fa: @js($fa),
            faNum(n) {
                const s = String(n);
                return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s;
            },
            init() {
                this.ac = new AbortController();
                this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                this.$el.querySelectorAll('[data-ripple]').forEach((el) => {
                    el.addEventListener('pointerdown', (e) => this.spawn(el, e.clientX, e.clientY), { signal: this.ac.signal });
                    el.addEventListener('keydown', (e) => {
                        if (e.key !== 'Enter' && e.key !== ' ') return;
                        const r = el.getBoundingClientRect();
                        this.spawn(el, r.left + r.width / 2, r.top + r.height / 2, true);
                    }, { signal: this.ac.signal });
                });
            },
            spawn(el, cx, cy, center) {
                if (this.mq.matches) return;
                const r = el.getBoundingClientRect();
                const x = cx - r.left, y = cy - r.top;
                const far = Math.max(
                    Math.hypot(x, y), Math.hypot(r.width - x, y),
                    Math.hypot(x, r.height - y), Math.hypot(r.width - x, r.height - y));
                const size = Math.ceil(far * 2 + 8);
                const ink = document.createElement('span');
                ink.className = 'mfrp-ink';
                ink.style.width = size + 'px';
                ink.style.height = size + 'px';
                ink.style.left = x + 'px';
                ink.style.top = y + 'px';
                ink.addEventListener('animationend', () => {
                    ink.remove();
                    this.inks = this.inks.filter((n) => n !== ink);
                }, { signal: this.ac.signal });
                el.appendChild(ink);
                this.inks.push(ink);
                this.last = (center ? 'center' : 'pointer') + ' · ' + this.faNum('x ' + Math.round(x) + ' · y ' + Math.round(y) + ' · d ' + size + 'px');
            },
            destroy() {
                if (this.ac) this.ac.abort();
                this.inks.forEach((n) => n.remove());
                this.inks = [];
            },
        }">
        <article class="mfrp-card mfrp-surf" data-ripple>
            <div class="mfrp-head">
                <h3>{{ $say('Atlas Studio — Q3 invoice', 'اطلس استودیو — صورت‌حساب فصل ۳') }}</h3>
                <span class="mfrp-total">{{ $say('€4,200', '۴٬۲۰۰ یورو') }}</span>
            </div>

            <div class="mfrp-tabs" role="tablist"
                x-bind:aria-label="@js($say('Invoice sections', 'بخش‌های صورت‌حساب'))">
                <button type="button" class="mfrp-tab mfrp-surf" role="tab" data-ripple
                    x-bind:aria-selected="tab === 'overview' ? 'true' : 'false'"
                    x-on:click="tab = 'overview'">{{ $say('Overview', 'خلاصه') }}</button>
                <button type="button" class="mfrp-tab mfrp-surf" role="tab" data-ripple
                    x-bind:aria-selected="tab === 'activity' ? 'true' : 'false'"
                    x-on:click="tab = 'activity'">{{ $say('Activity', 'فعالیت') }}</button>
                <button type="button" class="mfrp-tab mfrp-surf" role="tab" data-ripple
                    x-bind:aria-selected="tab === 'billing' ? 'true' : 'false'"
                    x-on:click="tab = 'billing'">{{ $say('Billing', 'صورتحساب') }}</button>
            </div>

            <p class="mfrp-panel" x-show="tab === 'overview'">
                {{ $say('3 projects · 18 invoices · average turnaround 2.4 days. The card itself is a ripple surface — press it anywhere.', '۳ پروژه · ۱۸ فاکتور · میانگین تحویل ۲٫۴ روز. خود کارت هم سطح موج است — هر جایش را فشار دهید.') }}
            </p>
            <p class="mfrp-panel" x-show="tab === 'activity'" x-cloak>
                {{ $say('PDF export run by Lena Ortiz, 4 minutes ago. Next scheduled export: Friday 18:00 UTC.', 'خروجی PDF را لنا اورتیز ۴ دقیقه پیش گرفت. خروجی بعدی: جمعه ۱۸:۰۰ UTC.') }}
            </p>
            <p class="mfrp-panel" x-show="tab === 'billing'" x-cloak>
                {{ $say('Atlas Studio BV · Prinsengracht 1, Amsterdam · VAT NL8612… · Net 14 terms.', 'اطلس استودیو BV · پرینسنگراخت ۱، آمستردام · شمارهٔ مالیاتی NL8612… · پرداخت ۱۴ روزه.') }}
            </p>

            <div class="mfrp-actions">
                <button type="button" class="mfrp-primary mfrp-surf" data-ripple>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v12"/><path d="m7 11 5 5 5-5"/><path d="M5 20h14"/></svg>
                    {{ $say('Download PDF', 'دانلود PDF') }}
                </button>
                <button type="button" class="mfrp-ghost mfrp-surf" data-ripple>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="6" cy="12" r="2.6"/><circle cx="18" cy="6" r="2.6"/><circle cx="18" cy="18" r="2.6"/><path d="m8.3 10.8 7.4-3.6"/><path d="m8.3 13.2 7.4 3.6"/></svg>
                    {{ $say('Share', 'اشتراک‌گذاری') }}
                </button>
            </div>
        </article>

        <p class="mfrp-hint" role="status" x-text="last || idle"></p>
    </div>
</section>
