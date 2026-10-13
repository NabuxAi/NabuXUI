{{--
    The onboarding flow's real scenario: a Meridian workspace wizard — welcome,
    role pick, personalization chips and a summary confirm inside one frame,
    with a segmented progress bar, a Back that never dies, a step slide that
    mirrors in RTL, and a finish message that takes the frame over.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .flonb-root {
        --flonb-accent: #4f46e5; --flonb-accent-soft: #eef0fe; --flonb-on-accent: #fff;
        --flonb-text: #191b2a; --flonb-muted: #666b83;
        --flonb-border: #e4e6ef; --flonb-surface: #fff; --flonb-layer: #f5f6fb;
        --flonb-ok: #0f8a43; --flonb-ok-soft: #e7f6ec;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .flonb-root {
        --flonb-accent: #8e94f8; --flonb-accent-soft: #262a4d; --flonb-on-accent: #14152b;
        --flonb-text: #ecedf6; --flonb-muted: #a6abc4;
        --flonb-border: #34364e; --flonb-surface: #1b1c2e; --flonb-layer: #222439;
        --flonb-ok: #5bd08d; --flonb-ok-soft: #1d3a2a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flonb-root {
            --flonb-accent: #8e94f8; --flonb-accent-soft: #262a4d; --flonb-on-accent: #14152b;
            --flonb-text: #ecedf6; --flonb-muted: #a6abc4;
            --flonb-border: #34364e; --flonb-surface: #1b1c2e; --flonb-layer: #222439;
            --flonb-ok: #5bd08d; --flonb-ok-soft: #1d3a2a;
        }
    }
    .flonb-frame { inline-size: min(100%, 30rem); display: grid; gap: 1.25rem; padding: 1.5rem;
                   border: 1px solid var(--flonb-border); border-radius: 1.4rem;
                   background: var(--flonb-surface); color: var(--flonb-text);
                   box-shadow: 0 18px 40px -28px rgb(20 22 48 / .35); }
    .flonb-top { display: grid; gap: .5rem; }
    .flonb-bar { display: flex; gap: .3rem; }
    .flonb-bar i { flex: 1; block-size: 4px; border-radius: 999px; background: var(--flonb-border);
                   transition: background-color .3s ease; }
    .flonb-bar i[data-on] { background: var(--flonb-accent); }
    .flonb-meta { display: flex; justify-content: space-between; gap: .75rem; font-size: .78rem; color: var(--flonb-muted); }
    .flonb-stage { display: grid; min-block-size: 15.5rem; align-items: start; }
    .flonb-step { grid-area: 1 / 1; display: grid; gap: .65rem;
                  transition: translate .34s cubic-bezier(.2, 0, 0, 1), opacity .34s ease, visibility .34s; }
    .flonb-step[data-pos="before"] { translate: -28px 0; opacity: 0; visibility: hidden; pointer-events: none; }
    .flonb-step[data-pos="after"] { translate: 28px 0; opacity: 0; visibility: hidden; pointer-events: none; }
    html[dir="rtl"] .flonb-step[data-pos="before"] { translate: 28px 0; }
    html[dir="rtl"] .flonb-step[data-pos="after"] { translate: -28px 0; }
    .flonb-kicker { display: inline-flex; align-items: center; gap: .4rem; justify-self: start;
                    padding: .3rem .75rem; border-radius: 999px; background: var(--flonb-accent-soft);
                    color: var(--flonb-accent); font-size: .75rem; font-weight: 700; }
    .flonb-step h4 { margin: 0; font: 700 var(--nx-text-xl, 1.25rem) / 1.35 var(--nx-font-display, inherit); }
    .flonb-step > p { margin: 0; color: var(--flonb-muted); font-size: .9rem; line-height: 1.7; }
    .flonb-list { display: grid; gap: .5rem; margin: .35rem 0 0; padding: 0; list-style: none; }
    .flonb-list li { display: flex; align-items: center; gap: .55rem; font-size: .88rem; color: var(--flonb-text); }
    .flonb-list svg { flex: none; inline-size: 1.05rem; color: var(--flonb-ok); }
    .flonb-roles { display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; margin-block-start: .35rem; }
    .flonb-role { position: relative; display: grid; gap: .2rem; padding: .85rem .8rem; text-align: start;
                  border: 1px solid var(--flonb-border); border-radius: 1rem; background: var(--flonb-surface);
                  color: var(--flonb-text); font: inherit; cursor: pointer;
                  transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease; }
    .flonb-role:hover { background: var(--flonb-layer); }
    .flonb-role b { font-size: .88rem; }
    .flonb-role small { font-size: .74rem; color: var(--flonb-muted); }
    .flonb-role i { position: absolute; inset-block-start: .5rem; inset-inline-end: .5rem; display: grid;
                    place-items: center; inline-size: 1.15rem; aspect-ratio: 1; border-radius: 50%;
                    background: var(--flonb-accent); color: var(--flonb-on-accent); font-size: .62rem;
                    font-style: normal; visibility: hidden; scale: .5; transition: scale .18s ease, visibility .18s; }
    .flonb-role[data-on] { border-color: var(--flonb-accent); box-shadow: inset 0 0 0 1px var(--flonb-accent); }
    .flonb-role[data-on] i { visibility: visible; scale: 1; }
    .flonb-role:focus-visible, .flonb-chip:focus-visible, .flonb-btn:focus-visible { outline: 2px solid var(--flonb-accent); outline-offset: 2px; }
    .flonb-chips { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-start: .35rem; }
    .flonb-chip { padding: .48rem .95rem; border: 1px solid var(--flonb-border); border-radius: 999px;
                  background: var(--flonb-surface); color: var(--flonb-text); font: 600 .8rem / 1 inherit;
                  cursor: pointer; transition: background-color .18s ease, border-color .18s ease, color .18s ease; }
    .flonb-chip:hover { border-color: var(--flonb-accent); }
    .flonb-chip[aria-pressed="true"] { background: var(--flonb-accent); border-color: var(--flonb-accent); color: var(--flonb-on-accent); }
    .flonb-sum { display: grid; margin: .35rem 0 0; }
    .flonb-sum > div { display: grid; grid-template-columns: auto 1fr; justify-content: start; gap: .35rem 1rem;
                       padding-block: .6rem; border-block-start: 1px solid var(--flonb-border); font-size: .88rem; }
    .flonb-sum dt { color: var(--flonb-muted); }
    .flonb-sum dd { margin: 0; font-weight: 700; overflow-wrap: anywhere; }
    .flonb-nav { display: flex; align-items: center; gap: .75rem; }
    .flonb-btn { display: inline-flex; align-items: center; justify-content: center; gap: .45rem; min-block-size: 2.7rem;
                 padding-inline: 1.35rem; border: none; border-radius: 999px; background: var(--flonb-layer);
                 color: var(--flonb-text); font: 600 .88rem / 1 inherit; cursor: pointer;
                 transition: background-color .18s ease, opacity .18s ease; }
    .flonb-btn:hover:not(:disabled) { background: var(--flonb-border); }
    .flonb-btn:disabled { opacity: .45; cursor: not-allowed; }
    .flonb-btn[data-kind="go"] { margin-inline-start: auto; background: var(--flonb-accent); color: var(--flonb-on-accent); }
    .flonb-btn[data-kind="go"]:hover:not(:disabled) { background: var(--flonb-accent); filter: brightness(1.08); }
    .flonb-btn svg { inline-size: 1rem; }
    html[dir="rtl"] .flonb-btn svg { rotate: 180deg; }
    .flonb-done { justify-items: center; text-align: center; gap: .8rem; }
    .flonb-done-ring { display: grid; place-items: center; inline-size: 4.5rem; aspect-ratio: 1;
                       border-radius: 50%; background: var(--flonb-ok-soft); }
    .flonb-done-ring svg { inline-size: 2.4rem; color: var(--flonb-ok); }
    .flonb-done-ring path { stroke-dasharray: 30; stroke-dashoffset: 30; animation: flonb-draw .5s ease-out .1s forwards; }
    .flonb-done-ring circle { stroke-dasharray: 76; stroke-dashoffset: 76; animation: flonb-draw .6s ease-out forwards; }
    .flonb-hint { margin: 0; max-inline-size: 54ch; text-align: center; font-size: .82rem; color: var(--nx-text-muted); }
    @keyframes flonb-draw { to { stroke-dashoffset: 0; } }
    @media (max-width: 480px) {
        .flonb-frame { padding: 1.1rem; border-radius: 1.1rem; }
        .flonb-stage { min-block-size: 19rem; }
        .flonb-roles { grid-template-columns: 1fr; }
        .flonb-nav { flex-wrap: wrap; }
        .flonb-btn { flex: 1 1 auto; padding-inline: 1rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .flonb-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="flonb-root"
    x-data="{
        fa: {{ $fa ? 'true' : 'false' }},
        step: 0,
        role: null,
        picks: [],
        done: false,
        roles: {{ json_encode([
            'designer' => $say('Product designer', 'طراح محصول'),
            'engineer' => $say('Engineer', 'مهندس'),
            'manager' => $say('Product manager', 'مدیر محصول'),
            'marketer' => $say('Marketer', 'بازاریاب'),
        ], JSON_UNESCAPED_UNICODE) }},
        topics: {{ json_encode([
            'systems' => $say('Design systems', 'سیستم‌های طراحی'),
            'motion' => $say('Motion', 'موشن'),
            'a11y' => $say('Accessibility', 'دسترس‌پذیری'),
            'speed' => $say('Performance', 'کارایی'),
            'tokens' => $say('Design tokens', 'توکن‌های طراحی'),
            'handoff' => $say('Handoff', 'تحویل طراحی'),
        ], JSON_UNESCAPED_UNICODE) }},
        n(v) { const s = String(v); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        pos(i) { return i === this.step ? 'now' : (i < this.step ? 'before' : 'after'); },
        toggle(t) { this.picks = this.picks.includes(t) ? this.picks.filter((p) => p !== t) : [...this.picks, t]; },
        get pickedNames() { return this.picks.map((p) => this.topics[p]).join(' · '); },
        get canNext() { return this.step === 1 ? !!this.role : this.step === 2 ? this.picks.length > 0 : true; },
        next() { this.step < 3 ? this.step++ : this.done = true; },
        reset() { this.step = 0; this.role = null; this.picks = []; this.done = false; },
    }">
    <section class="pg-box" style="gap: 1.25rem; justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('From hello to a ready workspace', 'از «سلام» تا ورک‌اسپیس آماده') }}</h3>
            <p style="margin: 0; max-inline-size: 54ch; color: var(--nx-text-muted)">
                {{ $say('A real four-step wizard with Alpine state only: the progress bar fills as you move, Continue stays locked until the step is honest, Back works from anywhere, and the final screen draws its check while the steps slide in a direction that mirrors in Persian.', 'یک ویزارد واقعی چهارقدمی فقط با وضعیت Alpine: نوار پیشرفت با حرکت شما پر می‌شود، ادامه تا روراست نبودن قدم قفل است، برگشت از همه‌جا کار می‌کند و صحنهٔ پایانی تیکش را ترسیم می‌کند در حالی که قدم‌ها از جهتی می‌آیند که در فارسی آینه می‌شود.') }}
            </p>
        </div>

        <div class="flonb-frame" x-show="!done">
            <header class="flonb-top">
                <div class="flonb-bar" role="progressbar" :aria-valuenow="step + 1" aria-valuemin="1" aria-valuemax="4"
                    aria-label="{{ $say('Onboarding progress', 'پیشرفت راه‌اندازی') }}">
                    <i data-on></i>
                    <i :data-on="step >= 1 ? '' : null"></i>
                    <i :data-on="step >= 2 ? '' : null"></i>
                    <i :data-on="step >= 3 ? '' : null"></i>
                </div>
                <div class="flonb-meta">
                    <span>{{ $say('Step', 'قدم') }} <span x-text="n(step + 1)"></span> {{ $say('of', 'از') }} {{ $num('4') }}</span>
                    <span x-show="step === 1">{{ $say('Pick one', 'یکی را بردار') }}</span>
                    <span x-show="step === 2">{{ $say('One or more', 'یکی یا چندتا') }}</span>
                    <span x-show="step === 3">{{ $say('Last look', 'نگاه آخر') }}</span>
                </div>
            </header>

            <div class="flonb-stage">
                <section class="flonb-step" :data-pos="pos(0)" aria-hidden="false">
                    <span class="flonb-kicker">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z"/></svg>
                        {{ $say('Meridian', 'مریدین') }}
                    </span>
                    <h4>{{ $say('Welcome to your team’s new home', 'به خانهٔ جدید تیم‌تان خوش آمدید') }}</h4>
                    <p>{{ $say('Four short steps and the workspace fits how you work — no credit card, no admin tickets.', 'چهار قدم کوتاه و ورک‌اسپیس با شیوهٔ کار شما جور می‌شود — نه کارت بانکی، نه تیکت برای ادمین.') }}</p>
                    <ul class="flonb-list">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                            {{ $say('Dashboards tuned to your role', 'داشبورد هماهنگ با نقش شما') }}
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                            {{ $say('Starter kits picked from real projects', 'کیت‌های آغازین برگرفته از پروژه‌های واقعی') }}
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                            {{ $say('Editable any time from settings', 'هر وقت خواستید از تنظیمات تغییرش بدهید') }}
                        </li>
                    </ul>
                </section>

                <section class="flonb-step" :data-pos="pos(1)">
                    <h4>{{ $say('What do you do here?', 'اینجا کارِ شما چیست؟') }}</h4>
                    <p>{{ $say('We use this once — to choose which dashboard greets you first.', 'فقط یک بار می‌پرسیم — تا بدانیم اولین دیدن، کدام داشبورد باشد.') }}</p>
                    <div class="flonb-roles" role="radiogroup" aria-label="{{ $say('Your role', 'نقش شما') }}">
                        @foreach ([
                            'designer' => [$say('Product designer', 'طراح محصول'), $say('Shapes the interface', 'شکل رابط را می‌سازد')],
                            'engineer' => [$say('Engineer', 'مهندس'), $say('Ships the code', 'کد را می‌رساند')],
                            'manager' => [$say('Product manager', 'مدیر محصول'), $say('Owns the roadmap', 'مسیر محصول را نگه می‌دارد')],
                            'marketer' => [$say('Marketer', 'بازاریاب'), $say('Tells the story', 'داستان را روایت می‌کند')],
                        ] as $id => [$name, $note])
                            <button type="button" class="flonb-role" role="radio" :aria-checked="role === '{{ $id }}' ? 'true' : 'false'"
                                :data-on="role === '{{ $id }}' ? '' : null" x-on:click="role = '{{ $id }}'">
                                <b>{{ $name }}</b>
                                <small>{{ $note }}</small>
                                <i aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.5l5 5 10-11"/></svg>
                                </i>
                            </button>
                        @endforeach
                    </div>
                </section>

                <section class="flonb-step" :data-pos="pos(2)">
                    <h4>{{ $say('Tune your feed', 'فید خود را کوک کنید') }}</h4>
                    <p>{{ $say('Pick what you want landing on your desk every Monday.', 'انتخاب کنید هر دوشنبه چه چیزی روی میزِ شما بنشیند.') }}</p>
                    <div class="flonb-chips" role="group" aria-label="{{ $say('Interests', 'علاقه‌ها') }}">
                        @foreach ([
                            'systems' => $say('Design systems', 'سیستم‌های طراحی'),
                            'motion' => $say('Motion', 'موشن'),
                            'a11y' => $say('Accessibility', 'دسترس‌پذیری'),
                            'speed' => $say('Performance', 'کارایی'),
                            'tokens' => $say('Design tokens', 'توکن‌های طراحی'),
                            'handoff' => $say('Handoff', 'تحویل طراحی'),
                        ] as $id => $label)
                            <button type="button" class="flonb-chip" :aria-pressed="picks.includes('{{ $id }}') ? 'true' : 'false'"
                                x-on:click="toggle('{{ $id }}')">{{ $label }}</button>
                        @endforeach
                    </div>
                </section>

                <section class="flonb-step" :data-pos="pos(3)">
                    <h4>{{ $say('Last look before we build it', 'نگاه آخر پیش از ساخت') }}</h4>
                    <p>{{ $say('Everything below is editable later — this is just the honest summary.', 'همهٔ این‌ها بعداً هم قابل تغییر است — این فقط خلاصهٔ روراست است.') }}</p>
                    <dl class="flonb-sum">
                        <div>
                            <dt>{{ $say('Role', 'نقش') }}</dt>
                            <dd x-text="role ? roles[role] : '—'"></dd>
                        </div>
                        <div>
                            <dt>{{ $say('Interests', 'علاقه‌ها') }}</dt>
                            <dd x-text="pickedNames || '—'"></dd>
                        </div>
                        <div>
                            <dt>{{ $say('Time to finish', 'زمان تا پایان') }}</dt>
                            <dd>{{ $say('under 5 seconds', 'کمتر از ۵ ثانیه') }}</dd>
                        </div>
                    </dl>
                </section>
            </div>

            <footer class="flonb-nav">
                <button type="button" class="flonb-btn" x-on:click="step = Math.max(0, step - 1)" :disabled="step === 0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6-6l-6 6 6 6"/></svg>
                    {{ $say('Back', 'برگشت') }}
                </button>
                <button type="button" class="flonb-btn" data-kind="go" x-on:click="next()" :disabled="!canNext" x-text="step < 3 ? '{{ $say('Continue', 'ادامه') }}' : '{{ $say('Create workspace', 'ساخت ورک‌اسپیس') }}'"></button>
            </footer>
        </div>

        <div class="flonb-frame flonb-done" x-show="done" x-cloak>
            <span class="flonb-done-ring" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="24" cy="24" r="15.9" stroke-width="2"/>
                    <path d="M15.5 24.5l5.5 5.5 11.5-12"/>
                </svg>
            </span>
            <h4 style="margin: 0; font: 700 var(--nx-text-xl, 1.25rem) / 1.35 var(--nx-font-display, inherit)">{{ $say('Your workspace is ready', 'ورک‌اسپیس شما آماده شد') }}</h4>
            <p style="margin: 0; color: var(--flonb-muted); font-size: .9rem">
                {{ $say('Meridian tuned itself for', 'مریدین خودش را کوک کرد برای') }}
                <b x-text="role ? roles[role] : '—'"></b>{{ $say('s — your first digest lands Monday 9:00.', '‌ها — نخستین چکیده دوشنبه ساعت ۹:۰۰ می‌رسد.') }}
            </p>
            <button type="button" class="flonb-btn" data-kind="go" x-on:click="reset()">{{ $say('Enter the dashboard', 'ورود به داشبورد') }}</button>
        </div>

        <p class="flonb-hint">{{ $say('Try it in Persian too: the steps arrive from the mirrored side, the numbers go Persian, and nothing else changes.', 'فارسی‌اش را هم امتحان کنید: قدم‌ها از سمت آینه‌شده می‌آیند، اعداد فارسی می‌شوند و هیچ‌چیز دیگری عوض نمی‌شود.') }}</p>
    </section>
</div>
