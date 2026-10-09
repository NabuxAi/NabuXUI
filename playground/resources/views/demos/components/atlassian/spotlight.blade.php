{{--
    Atlassian's onboarding spotlight as a real three-step product tour over a
    mini Jira board: a moving hole dims everything but the teaching target,
    the dialog measures its target and sits above or below it, Skip and
    Next/Finish close the tour, Escape skips from anywhere.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $steps = [
        ['t' => $say('Create', 'ایجاد'), 'b' => $say('Every piece of work starts here. One click opens the issue form with the project pre-filled.', 'هر کاری از این‌جا شروع می‌شود. با یک کلیک، فرم مساله با پروژهٔ انتخاب‌شده باز می‌شود.')],
        ['t' => $say('The board', 'تخته'), 'b' => $say('Drag cards across the columns, or press J and K to walk them with the keyboard.', 'کارت‌ها را بین ستون‌ها بکشید، یا با J و K با کیبورد روی‌شان راه بروید.')],
        ['t' => $say('Watch', 'دنبال کردن'), 'b' => $say('Watching this board pages every change to you — turn it off whenever it gets loud.', 'دنبال کردن این تخته هر تغییری را به شما می‌رساند — هر وقت شلوغ شد خاموشش کنید.')],
    ];
@endphp
<style>
    .atsp-root {
        --atsp-blue: #0052CC; --atsp-ink: #172B4D; --atsp-ink-strong: #091E42;
        --atsp-muted: #626F86; --atsp-subtle: #44546F; --atsp-border: #DFE1E6; --atsp-hover: #F1F2F4;
        --atsp-surface: #FFFFFF; --atsp-page: #F7F8F9; --atsp-scrim: rgba(9, 30, 66, .77);
        font-family: Inter, system-ui, sans-serif; color: var(--atsp-ink);
    }
    html[data-theme="dark"] .atsp-root {
        --atsp-blue: #388BFF; --atsp-ink: #C7D1DB; --atsp-ink-strong: #E4EAF0; --atsp-muted: #8590A2;
        --atsp-subtle: #A9B8C4; --atsp-border: #2C3136; --atsp-hover: #1D2125;
        --atsp-surface: #161A1D; --atsp-page: #101214; --atsp-scrim: rgba(4, 9, 15, .78);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atsp-root {
            --atsp-blue: #388BFF; --atsp-ink: #C7D1DB; --atsp-ink-strong: #E4EAF0; --atsp-muted: #8590A2;
            --atsp-subtle: #A9B8C4; --atsp-border: #2C3136; --atsp-hover: #1D2125;
            --atsp-surface: #161A1D; --atsp-page: #101214; --atsp-scrim: rgba(4, 9, 15, .78);
        }
    }
    .atsp-root :focus-visible { outline: 2px solid var(--atsp-blue); outline-offset: 2px; }

    .atsp-start { display: grid; justify-items: center; gap: .4rem; margin-block-end: .25rem; }
    .atsp-start button { block-size: 2rem; padding-inline: .9rem; border: none; border-radius: 3px; cursor: pointer;
                         background: var(--atsp-blue); color: #fff; font: 500 .8rem/1 Inter, system-ui; }
    .atsp-start button:hover { background: color-mix(in srgb, var(--atsp-blue) 88%, black); }
    .atsp-start p { margin: 0; font: 400 .74rem/1.5 Inter, system-ui; color: var(--nx-text-muted); }

    .atsp-stage { position: relative; isolation: isolate; overflow: clip; inline-size: min(100%, 36rem); margin-inline: auto;
                  border: 1px solid var(--atsp-border); border-radius: 6px; background: var(--atsp-page);
                  padding: .9rem; display: grid; gap: .75rem; }
    .atsp-bar { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
    .atsp-bar h4 { margin: 0; font: 500 .95rem/1.3 "Charlie Display", Inter, system-ui; color: var(--atsp-ink-strong); margin-inline-end: auto; }
    .atsp-create { block-size: 1.9rem; padding-inline: .8rem; border: none; border-radius: 3px; cursor: pointer;
                   background: var(--atsp-blue); color: #fff; font: 500 .78rem/1 Inter, system-ui; }
    .atsp-watch { position: relative; display: grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1;
                  border: none; border-radius: 50%; background: var(--atsp-hover); color: var(--atsp-subtle);
                  font: 600 .68rem Inter, system-ui; cursor: pointer; }
    .atsp-board { display: grid; gap: .6rem; grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .atsp-col { border: 1px solid var(--atsp-border); border-radius: 4px; background: var(--atsp-surface); padding: .5rem; display: grid; gap: .45rem; align-content: start; }
    .atsp-col > small { font: 700 .66rem/1.4 Inter, system-ui; letter-spacing: .4px; color: var(--atsp-muted); display: flex; gap: .3rem; align-items: center; }
    .atsp-col > small i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: var(--atsp-blue); }
    .atsp-card { border: 1px solid var(--atsp-border); border-radius: 3px; padding: .45rem .5rem; font: 400 .72rem/1.4 Inter, system-ui; color: var(--atsp-ink); background: var(--atsp-surface); }
    .atsp-card b { display: block; font: 600 .62rem Inter, system-ui; color: var(--atsp-blue); margin-block-end: .15rem; }

    .atsp-veil { position: absolute; z-index: 2; inset-block-start: 0; inset-inline-start: 0; border-radius: 6px;
                 pointer-events: none; opacity: 0; transition: all .35s cubic-bezier(.2, 0, 0, 1); }
    .atsp-veil.on { opacity: 1;
                    box-shadow: 0 0 0 4px color-mix(in srgb, var(--atsp-blue) 40%, transparent), 0 0 0 200vmax var(--atsp-scrim); }
    .atsp-veil::after { content: ''; position: absolute; inset: -6px; border: 2px solid var(--atsp-blue); border-radius: 9px;
                        animation: atsp-pulse 1.6s ease-out infinite; }
    @keyframes atsp-pulse { 0% { opacity: .9; scale: .97; } 70% { opacity: 0; scale: 1.06; } 100% { opacity: 0; scale: 1.06; } }

    .atsp-pop { position: absolute; z-index: 3; inline-size: min(16.5rem, calc(100% - 1rem));
                padding: .9rem; border-radius: 4px; background: var(--atsp-surface); text-align: start;
                box-shadow: 0 8px 12px rgba(9, 30, 66, .16), 0 0 1px rgba(9, 30, 66, .31);
                opacity: 0; pointer-events: none; transition: opacity .25s ease, inset-block-start .35s cubic-bezier(.2, 0, 0, 1), inset-inline-start .35s cubic-bezier(.2, 0, 0, 1), translate .35s cubic-bezier(.2, 0, 0, 1); }
    .atsp-pop.on { opacity: 1; pointer-events: auto; }
    .atsp-pop[data-side='bottom'] { translate: 0 0; }
    .atsp-pop[data-side='top'] { translate: 0 -100%; }
    .atsp-shot { inline-size: 100%; block-size: 3.4rem; border-radius: 3px; background: color-mix(in srgb, var(--atsp-blue) 10%, transparent);
                 display: grid; place-items: center; margin-block-end: .6rem; }
    .atsp-shot svg { inline-size: 100%; block-size: 100%; fill: none; stroke: var(--atsp-blue); stroke-width: 1.6; stroke-linecap: round; stroke-linejoin: round; }
    .atsp-pop h5 { margin: 0 0 .2rem; font: 600 .86rem/1.4 Inter, system-ui; color: var(--atsp-ink-strong); }
    .atsp-pop > p { margin: 0 0 .7rem; font: 400 .76rem/1.6 Inter, system-ui; color: var(--atsp-subtle); }
    .atsp-foot { display: flex; align-items: center; gap: .5rem; }
    .atsp-dots { display: flex; gap: .3rem; }
    .atsp-dots i { inline-size: .4rem; aspect-ratio: 1; border-radius: 999px; background: var(--atsp-border); transition: background .2s ease, inline-size .2s ease; }
    .atsp-dots i[data-current] { background: var(--atsp-blue); inline-size: .9rem; }
    .atsp-skip { margin-inline-start: auto; border: none; background: none; cursor: pointer; padding: .3rem .4rem; border-radius: 3px;
                 font: 500 .76rem Inter, system-ui; color: var(--atsp-muted); }
    .atsp-skip:hover { background: var(--atsp-hover); color: var(--atsp-ink); }
    .atsp-next { border: none; border-radius: 3px; block-size: 1.85rem; padding-inline: .8rem; cursor: pointer;
                 background: var(--atsp-blue); color: #fff; font: 500 .76rem/1 Inter, system-ui; }
    .atsp-next:hover { background: color-mix(in srgb, var(--atsp-blue) 88%, black); }
    @media (max-width: 420px) {
        .atsp-board { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .atsp-card b { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .atsp-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atsp-root"
    x-data="{
        tour: false,
        step: 0,
        steps: @js($steps),
        nextLabel: @js($say('Next', 'بعدی')),
        finishLabel: @js($say('Finish', 'پایان')),
        hole: { x: 0, y: 0, w: 0, h: 0 },
        box: { x: 0, y: 0, side: 'bottom' },
        place() {
            const t = this.$refs['t' + this.step];
            const st = this.$refs.stage;
            if (!t || !st) return;
            const sr = st.getBoundingClientRect();
            const tr = t.getBoundingClientRect();
            const pad = 6;
            this.hole = { x: tr.left - sr.left - pad, y: tr.top - sr.top - pad, w: tr.width + pad * 2, h: tr.height + pad * 2 };
            const side = (sr.bottom - tr.bottom) > 210 ? 'bottom' : 'top';
            const bw = Math.min(264, sr.width - 16);
            const x = Math.min(Math.max(tr.left - sr.left, 8), Math.max(8, sr.width - bw - 8));
            this.box = {
                x,
                y: side === 'bottom' ? tr.bottom - sr.top + 14 : tr.top - sr.top - 14,
                side,
            };
        },
        begin() { this.tour = true; this.step = 0; this.$nextTick(() => this.place()); },
        go(i) {
            if (i < 0 || i >= this.steps.length) { this.tour = false; return; }
            this.step = i;
            this.$nextTick(() => this.place());
        },
    }"
    x-on:keydown.escape.window="tour = false">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The tour that dims the world', 'توری که دنیا را تاریک می‌کند') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Three stops, one moving hole — the dialog measures its target and sits above or below it; Skip and Escape let people out at any step.', 'سه ایستگاه، یک سوراخِ متحرک — گفت‌وگو هدفش را اندازه می‌گیرد و بالا یا پایینش می‌نشیند؛ Skip و Escape در هر گامی راه خروج‌اند.') }}
            </p>
        </div>

        <div class="atsp-start">
            <button type="button" x-on:click="begin()">{{ $say('Start the tour', 'شروع تور') }}</button>
            <p>{{ $say('Runs against the board below — the veil never leaves this frame.', 'روی تختهٔ زیر اجرا می‌شود — پرده هرگز از این قاب بیرون نمی‌رود.') }}</p>
        </div>

        <div class="atsp-stage" x-ref="stage">
            <div class="atsp-bar">
                <h4>{{ $say('Sprint 24 board', 'تختهٔ اسپرینت ۲۴') }}</h4>
                <button type="button" class="atsp-create" x-ref="t0">{{ $say('Create', 'ایجاد') }}</button>
                <button type="button" class="atsp-watch" x-ref="t2" aria-label="{{ $say('Watch board', 'دنبال کردن تخته') }}">۲</button>
            </div>
            <div class="atsp-board">
                <div class="atsp-col">
                    <small><i aria-hidden="true"></i>{{ $say('To do', 'در انتظار') }}</small>
                    <div class="atsp-card" x-ref="t1"><b>NABU-247</b>{{ $say('Search tokens', 'توکن‌های جست‌وجو') }}</div>
                    <div class="atsp-card"><b>NABU-253</b>{{ $say('Dark audit', 'ممیزی تم تیره') }}</div>
                </div>
                <div class="atsp-col">
                    <small><i aria-hidden="true"></i>{{ $say('In progress', 'در جریان') }}</small>
                    <div class="atsp-card"><b>NABU-241</b>{{ $say('Payment webhook', 'وب‌هوک پرداخت') }}</div>
                </div>
                <div class="atsp-col">
                    <small><i aria-hidden="true"></i>{{ $say('Done', 'انجام شد') }}</small>
                    <div class="atsp-card"><b>NABU-238</b>{{ $say('Seed script', 'اسکریپت سید') }}</div>
                </div>
            </div>

            <div class="atsp-veil" :class="tour ? 'on' : null"
                 :style="`inset-inline-start: ${hole.x}px; inset-block-start: ${hole.y}px; inline-size: ${hole.w}px; block-size: ${hole.h}px`"
                 aria-hidden="true"></div>

            <div class="atsp-pop" role="dialog" aria-label="{{ $say('Onboarding step', 'گام آنبوردینگ') }}"
                 :class="tour ? 'on' : null" :data-side="box.side"
                 :style="`inset-inline-start: ${box.x}px; inset-block-start: ${box.y}px`">
                <div class="atsp-shot" aria-hidden="true">
                    <svg viewBox="0 0 240 54">
                        <rect x="8" y="10" width="86" height="30" rx="4"/><path d="M20 25h58"/>
                        <path d="M118 25h60"/><path d="M170 17l10 8-10 8"/>
                        <rect x="196" y="8" width="36" height="34" rx="6"/><circle cx="214" cy="25" r="9"/>
                    </svg>
                </div>
                <h5 x-text="steps[step].t"></h5>
                <p x-text="steps[step].b"></p>
                <div class="atsp-foot">
                    <div class="atsp-dots" aria-hidden="true">
                        <template x-for="(s, i) in steps"><i :data-current="i === step ? '' : null"></i></template>
                    </div>
                    <button type="button" class="atsp-skip" x-on:click="tour = false">{{ $say('Skip', 'رد کردن') }}</button>
                    <button type="button" class="atsp-next" x-on:click="go(step + 1)" x-text="step === steps.length - 1 ? finishLabel : nextLabel"></button>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Four placements, one rule', 'چهار جایگیری، یک قاعده') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The dialog always claims the side with more room — bottom, top, left or right — and the veil keeps the rest of the page at 77% ink.', 'گفت‌وگو همیشه سمتی با فضای بیشتر را برمی‌دارد — پایین، بالا، چپ یا راست — و پرده، بقیهٔ صفحه را در ۷۷٪ مرکب نگه می‌دارد.') }}
        </p>
    </div>
    <div class="atsp-root" style="inline-size: 100%">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center">
            @foreach ([['bottom', 'پایین'], ['top', 'بالا'], ['start', 'سمت شروع'], ['end', 'سمت پایان']] as [$side, $faSide])
                <figure style="margin: 0; display: grid; gap: .35rem; justify-items: center">
                    <div class="atsp-stage" style="inline-size: 12rem; min-block-size: 9.5rem; padding: .6rem; position: relative; overflow: clip">
                        <button type="button" class="atsp-create" style="margin-inline: auto; display: block">{{ $say('Target', 'هدف') }}</button>
                        <div class="atsp-veil on" style="inset-inline-start: 25%; inset-block-start: 18%; inline-size: 50%; block-size: 28%" aria-hidden="true"></div>
                    </div>
                    <figcaption style="font: 500 .72rem/1.4 Inter, system-ui; color: var(--nx-text-muted)">placement · {{ $fa ? $faSide : $side }}</figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
