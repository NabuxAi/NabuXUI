{{--
    split-flap's real scenario: the departure board of Atlas Rail, rebuilt as
    a Solari split-flap — every character is a cell with a top and a bottom
    half plus two falling leaves: the top leaf swings down on its hinge,
    the bottom leaf drops flat after it, 130ms + 105ms of pure mechanics,
    no sound. Type into the field and the board re-spells itself in a
    cascade; a live clock row flips only the digits that changed. The board
    speaks Latin caps like the real hardware — Persian stays word-level in
    the surrounding UI and never gets letter-split. Off-screen the clock
    parks; under reduced motion every flip lands instantly.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $nbsp = "\u{00a0}";
    $message = str_pad('BON VOYAGE', 10);
    $clockSeed = str_pad('0', 8);
@endphp

<style>
    .ensf-wrap {
        --ensf-fg: #1c1917; --ensf-muted: #78716c; --ensf-border: #e7e5e4;
        --ensf-bg: #ffffff; --ensf-accent: #b45309; --ensf-chip: #f5f0eb;
        display: grid; gap: 1rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--ensf-fg);
    }
    html[data-theme="dark"] .ensf-wrap {
        --ensf-fg: #fafaf9; --ensf-muted: #a8a29e; --ensf-border: #292524;
        --ensf-bg: #1c1917; --ensf-accent: #f59e0b; --ensf-chip: #231e1a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ensf-wrap {
            --ensf-fg: #fafaf9; --ensf-muted: #a8a29e; --ensf-border: #292524;
            --ensf-bg: #1c1917; --ensf-accent: #f59e0b; --ensf-chip: #231e1a;
        }
    }

    /* The board itself is hardware: dark in every theme, like the real thing. */
    .ensf-board {
        direction: ltr; display: grid; gap: .85rem;
        padding: clamp(.9rem, 3.5%, 1.35rem);
        border-radius: 1.05rem; border: 1px solid #3a3126;
        background: linear-gradient(180deg, #201c16, #171411 70%, #14110e);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .06), inset 0 -8px 24px rgba(0, 0, 0, .45);
    }
    .ensf-topline { display: flex; align-items: center; gap: .6rem; }
    .ensf-brand { font-size: .68rem; font-weight: 800; letter-spacing: .3em; color: #f2c98a; }
    .ensf-rule { flex: 1; block-size: 1px; background: linear-gradient(90deg, #4a3f2e, #2a2419); }
    .ensf-tag { font-size: .6rem; font-weight: 700; letter-spacing: .22em; color: #8a7d64;
                font-variant-numeric: tabular-nums; }
    .ensf-clock-label { font-size: .6rem; font-weight: 700; letter-spacing: .2em; color: #8a7d64; }

    .ensf-row { display: flex; gap: .22rem; }
    .ensf-msgrow { justify-content: start; }
    .ensf-clockrow { display: flex; align-items: center; gap: .7rem; }

    .ensf-cell { position: relative; flex: 1 1 0; min-inline-size: 0; max-inline-size: 1.5rem;
                 aspect-ratio: 1 / 1.72; perspective: 300px; container-type: inline-size; }
    .ensf-half, .ensf-leaf { position: absolute; inset-inline: 0; block-size: 50%; overflow: clip; }
    .ensf-half.ensf-top, .ensf-leaf.ensf-leaf-t { inset-block-start: 0;
        background: linear-gradient(180deg, #37332c, #2a2721); }
    .ensf-half.ensf-bot, .ensf-leaf.ensf-leaf-b { inset-block-end: 0;
        background: linear-gradient(180deg, #1f1c17, #29251f); }
    .ensf-leaf.ensf-leaf-t { transform-origin: 50% 100%; transform: rotateX(0deg); z-index: 2; display: none;
                             box-shadow: 0 1px 2px rgba(0, 0, 0, .5); }
    .ensf-leaf.ensf-leaf-b { transform-origin: 50% 0%; transform: rotateX(90deg); z-index: 2; display: none;
                             box-shadow: 0 1px 2px rgba(0, 0, 0, .5); }
    .ensf-g { position: absolute; inset-inline: 0; block-size: 200%;
              display: flex; align-items: center; justify-content: center;
              font: 700 clamp(.78rem, 4.6vw, 1.32rem)/1 ui-monospace, 'SF Mono', Menlo, Consolas, monospace;
              font-size: 64cqw;
              color: #f3ead8; font-variant-numeric: tabular-nums; }
    .ensf-top .ensf-g, .ensf-leaf-t .ensf-g { inset-block-start: 0; }
    .ensf-bot .ensf-g, .ensf-leaf-b .ensf-g { inset-block-start: -100%; }
    .ensf-cell::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline: 0;
                        block-size: 1px; background: rgba(0, 0, 0, .6); z-index: 3; pointer-events: none; }
    .ensf-row .ensf-cell:first-child { border-start-start-radius: .3rem; border-end-start-radius: .3rem; }

    .ensf-ctl { display: grid; gap: .6rem; }
    .ensf-type { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem .7rem; }
    .ensf-type label { font-size: .78rem; font-weight: 700; color: var(--ensf-muted); }
    .ensf-type input { padding: .5rem .8rem; border-radius: .7rem; border: 1px solid var(--ensf-border);
                       background: var(--ensf-bg); color: inherit; font: 700 .85rem/1.2
                       ui-monospace, 'SF Mono', Menlo, monospace; letter-spacing: .12em;
                       text-transform: uppercase; inline-size: min(11rem, 100%); }
    .ensf-type input:focus-visible { outline: 2px solid var(--ensf-accent); outline-offset: 2px; }
    .ensf-presets { display: flex; flex-wrap: wrap; gap: .45rem; }
    .ensf-preset { padding: .38rem .8rem; border-radius: 999px; border: 1px solid var(--ensf-border);
                   background: var(--ensf-chip); color: inherit; cursor: pointer;
                   font: 700 .72rem/1 ui-monospace, Menlo, monospace; letter-spacing: .1em;
                   transition: border-color .15s ease, color .15s ease; }
    .ensf-preset:hover { border-color: var(--ensf-accent); color: var(--ensf-accent); }
    .ensf-preset:focus-visible { outline: 2px solid var(--ensf-accent); outline-offset: 2px; }
    .ensf-hint { margin: 0; font-size: .74rem; line-height: 1.8; color: var(--ensf-muted); }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        msg: 'BON VOYAGE', draft: 'BON VOYAGE', clockStr: '--:--:--',
        visible: false, reduced: false,
        io: null, timer: 0, timers: [], msgCells: [], clockCells: [],
        init() {
            this.reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
            this.msgCells = this.cellsOf(this.$refs.msg);
            this.clockCells = this.cellsOf(this.$refs.clock);
            this.io = new IntersectionObserver((entries) => {
                this.visible = entries[0].isIntersecting;
            }, { threshold: 0.2 });
            this.io.observe(this.$refs.board);
            this.paintClock();
            this.timer = setInterval(() => this.tick(), 250);
        },
        destroy() {
            if (this.io) this.io.disconnect();
            clearInterval(this.timer);
            this.timers.forEach(clearTimeout);
            this.timers = [];
        },
        cellsOf(row) {
            return [...row.querySelectorAll('.ensf-cell')].map((el) => ({
                el: el, cur: el.dataset.ch ?? ' ', queue: [], busy: false,
            }));
        },
        paintClock() {
            const s = new Date().toLocaleTimeString('en-GB', { hour12: false });
            this.clockStr = s;
            this.clockCells.forEach((c, i) => {
                const ch = s[i] ?? ' ';
                c.cur = ch;
                this.putGlyph(c.el, ch);
            });
        },
        tick() {
            if (!this.visible) return;
            const s = new Date().toLocaleTimeString('en-GB', { hour12: false });
            if (s === this.clockStr) return;
            this.clockStr = s;
            this.clockCells.forEach((c, i) => {
                if (s[i] !== undefined) {
                    this.timers.push(setTimeout(() => this.setCell(c, s[i]), 28 * i));
                }
            });
        },
        norm(text) {
            return String(text ?? '').toUpperCase().replace(/[^A-Z0-9 :.\-·+]/g, ' ');
        },
        setType(text) {
            const n = this.norm(text).slice(0, this.msgCells.length);
            const t = n.padEnd(this.msgCells.length, ' ');
            this.msg = t;
            this.draft = n;
            this.timers.forEach(clearTimeout);
            this.timers = [];
            this.msgCells.forEach((c, i) => {
                this.timers.push(setTimeout(() => this.setCell(c, t[i]), 55 * i));
            });
        },
        putGlyph(el, ch) {
            const g = ch === ' ' ? '\u00a0' : ch;
            el.querySelector('.ensf-top .ensf-g').textContent = g;
            el.querySelector('.ensf-bot .ensf-g').textContent = g;
        },
        setCell(cell, ch) {
            cell.queue.push(ch);
            if (!cell.busy) this.runCell(cell);
        },
        runCell(cell) {
            let next = cell.queue.shift();
            while (next !== undefined) {
                if (next !== cell.cur) break;
                next = cell.queue.shift();
            }
            if (next === undefined) { cell.busy = false; return; }
            cell.busy = true;
            const leafT = cell.el.querySelector('.ensf-leaf-t');
            const leafB = cell.el.querySelector('.ensf-leaf-b');
            const glyph = next === ' ' ? '\u00a0' : next;
            if (this.reduced || !this.visible) {
                this.putGlyph(cell.el, next);
                cell.cur = next;
                cell.busy = false;
                this.runCell(cell);
                return;
            }
            leafT.firstElementChild.textContent = glyph;
            leafB.firstElementChild.textContent = glyph;
            leafT.style.display = 'block';
            const a1 = leafT.animate(
                [{ transform: 'rotateX(0deg)' }, { transform: 'rotateX(-90deg)' }],
                { duration: 130, easing: 'cubic-bezier(.5,0,.9,.45)' }
            );
            a1.onfinish = () => {
                cell.el.querySelector('.ensf-top .ensf-g').textContent = glyph;
                leafT.style.display = 'none';
                leafB.style.display = 'block';
                const a2 = leafB.animate(
                    [{ transform: 'rotateX(90deg)' }, { transform: 'rotateX(0deg)' }],
                    { duration: 105, easing: 'cubic-bezier(.15,.65,.3,1)' }
                );
                a2.onfinish = () => {
                    cell.el.querySelector('.ensf-bot .ensf-g').textContent = glyph;
                    leafB.style.display = 'none';
                    cell.cur = next;
                    cell.busy = false;
                    this.runCell(cell);
                };
            };
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A departure board that still clatters', 'تابلوی حرکت که هنوز تق‌تق می‌کند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Atlas Rail’s Solari board, rebuilt cell by cell: every character is a top and a bottom half with two falling leaves — the top leaf swings down on its hinge and the bottom drops flat after it, 130 plus 105 milliseconds of pure mechanics with no sound. Type into the field and the board re-spells itself in a cascade; the clock row flips only the digits that actually changed.', 'تابلوی سولاری اتلس ریل، خانه‌به‌خانه بازسازی شده: هر حرف یک نیم‌کرهٔ بالا و پایین دارد با دو برگِ افتان — برگ بالایی روی لولا پایین می‌چرخد و برگ پایینی بعد از آن صاف می‌افتد؛ ۱۳۰ به‌علاوهٔ ۱۰۵ میلی‌ثانیه مکانیکِ خالص، بدون صدا. در فیلد تایپ کنید تا تابلو آبشاری دوباره هجی کند؛ ردیف ساعت فقط رقمی را می‌چرخاند که واقعاً عوض شده.') }}
        </p>
    </div>

    <div class="ensf-wrap">
        <div class="ensf-board" x-ref="board">
            <div class="ensf-topline">
                <span class="ensf-brand">ATLAS RAIL</span>
                <span class="ensf-rule" aria-hidden="true"></span>
                <span class="ensf-tag">SPLIT-FLAP · DEPARTURES</span>
            </div>
            <div class="ensf-row ensf-msgrow" x-ref="msg" role="img"
                x-bind:aria-label="'message board: ' + msg.trim()">
                @foreach (str_split($message) as $ch)
                    <span class="ensf-cell" data-ch="{{ $ch }}">
                        <span class="ensf-half ensf-top"><span class="ensf-g">{{ $ch === ' ' ? $nbsp : $ch }}</span></span>
                        <span class="ensf-half ensf-bot"><span class="ensf-g">{{ $ch === ' ' ? $nbsp : $ch }}</span></span>
                        <span class="ensf-leaf ensf-leaf-t" aria-hidden="true"><span class="ensf-g"></span></span>
                        <span class="ensf-leaf ensf-leaf-b" aria-hidden="true"><span class="ensf-g"></span></span>
                    </span>
                @endforeach
            </div>
            <div class="ensf-clockrow">
                <span class="ensf-clock-label">{{ $say('LOCAL TIME', 'ساعت محلی') }}</span>
                <div class="ensf-row" x-ref="clock" role="img" x-bind:aria-label="'local time: ' + clockStr">
                    @foreach (str_split($clockSeed) as $ch)
                        <span class="ensf-cell" data-ch="{{ $ch }}">
                            <span class="ensf-half ensf-top"><span class="ensf-g">{{ $ch }}</span></span>
                            <span class="ensf-half ensf-bot"><span class="ensf-g">{{ $ch }}</span></span>
                            <span class="ensf-leaf ensf-leaf-t" aria-hidden="true"><span class="ensf-g"></span></span>
                            <span class="ensf-leaf ensf-leaf-b" aria-hidden="true"><span class="ensf-g"></span></span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="ensf-ctl">
            <div class="ensf-type">
                <label for="ensf-input">{{ $say('Retype the board', 'تابلو را دوباره بنویسید') }}</label>
                <input id="ensf-input" type="text" maxlength="10" spellcheck="false"
                    x-model="draft" x-on:input="setType($event.target.value)"
                    placeholder="{{ $say('BON VOYAGE', 'BON VOYAGE') }}">
            </div>
            <div class="ensf-presets">
                <button type="button" class="ensf-preset" x-on:click="draft = 'GATE 22'; setType(draft)">GATE 22</button>
                <button type="button" class="ensf-preset" x-on:click="draft = 'MILAN 7:40'; setType(draft)">MILAN 7:40</button>
                <button type="button" class="ensf-preset" x-on:click="draft = 'BON VOYAGE'; setType(draft)">BON VOYAGE</button>
            </div>
            <p class="ensf-hint">{{ $say('each cell: two leaves on one hinge · 130ms drop + 105ms settle · clock flips only changed digits · charset A–Z 0–9 : . - · + like the real hardware, so Persian never letter-splits', 'هر خانه: دو برگ روی یک لولا · ۱۳۰ms سقوط + ۱۰۵ms نشست · ساعت فقط رقم‌های عوض‌شده را می‌چرخاند · الفبای A–Z و ۰–۹ و : . - · + مثل سخت‌افزار واقعی، پس فارسی هرگز حرف‌به‌حرف نمی‌شکند') }}</p>
        </div>
    </div>
</section>
