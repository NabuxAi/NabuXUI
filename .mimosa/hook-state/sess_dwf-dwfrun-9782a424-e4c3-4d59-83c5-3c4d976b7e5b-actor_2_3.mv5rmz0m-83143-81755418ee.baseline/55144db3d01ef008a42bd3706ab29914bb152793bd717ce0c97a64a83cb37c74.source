{{--
    Terminal CTA in its real habitat: the developer band of a landing page —
    a terminal that types the install command character by character (then
    replays it), a copy button that swaps to a tick, and beside it the
    developer headline, a GitHub star button with a live counter and a docs
    link. Reduced motion lands the whole command at once, unanimated.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .cttr-root {
        --cttr-text: #141726; --cttr-muted: #6c7088; --cttr-border: #e5e7eb; --ctpr-card: #ffffff;
        --cttr-accent: #5647e6; --cttr-gold: #d98a1c; --cttr-gold-bright: #f4a93c;
        display: grid; gap: 2.25rem; justify-items: center; font-family: var(--nx-font-sans);
    }
    html[data-theme="dark"] .cttr-root {
        --cttr-text: #f7f7fa; --cttr-muted: #9a9db3; --cttr-border: #2a2d40; --ctpr-card: #141726;
        --cttr-accent: #8482fb; --cttr-gold: #f4a93c; --cttr-gold-bright: #ffd68a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cttr-root {
            --cttr-text: #f7f7fa; --cttr-muted: #9a9db3; --cttr-border: #2a2d40; --ctpr-card: #141726;
            --cttr-accent: #8482fb; --cttr-gold: #f4a93c; --cttr-gold-bright: #ffd68a;
        }
    }
    .cttr-wrap { display: grid; gap: 2rem; align-items: center;
                 grid-template-columns: repeat(auto-fit, minmax(19rem, 1fr)); inline-size: min(100%, 56rem); }
    .cttr-pitch { display: grid; gap: .9rem; justify-items: start; }
    .cttr-badge { display: inline-flex; align-items: center; gap: .45rem; padding: .35rem .8rem; border-radius: 999px;
                  background: var(--nx-accent-soft, #5647e61f); color: var(--cttr-accent);
                  font-size: .74rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .cttr-badge svg { inline-size: .95rem; block-size: .95rem; }
    .cttr-title { margin: 0; font: 800 clamp(1.5rem, 3.4vw, 2.1rem) / 1.2 var(--nx-font-display);
                  letter-spacing: var(--nx-tracking-tight); color: var(--cttr-text); text-wrap: balance; }
    .cttr-sub { margin: 0; max-inline-size: 40ch; color: var(--cttr-muted); font-size: var(--nx-text-sm); text-wrap: pretty; }
    .cttr-row { display: flex; flex-wrap: wrap; gap: .9rem; align-items: center; margin-block-start: .35rem; }
    .cttr-star { display: inline-flex; align-items: center; gap: .5rem; block-size: 2.75rem; padding-inline: 1.15rem;
                 border-radius: var(--nx-radius-lg); border: 1px solid var(--cttr-border); background: var(--ctpr-card);
                 color: var(--cttr-text); font: 600 var(--nx-text-sm) / 1 var(--nx-font-mono); cursor: pointer;
                 transition: translate .18s ease, border-color .18s ease, background-color .18s ease; }
    .cttr-star svg { inline-size: 1.05rem; block-size: 1.05rem; color: var(--cttr-gold); }
    .cttr-star:hover { translate: 0 -2px; border-color: var(--cttr-gold); box-shadow: 0 10px 22px #0a0c1726; }
    .cttr-star:active { translate: 0 0; }
    .cttr-star:focus-visible { outline: 2px solid var(--cttr-accent); outline-offset: 3px; }
    .cttr-star[data-on="true"] { border-color: var(--cttr-gold-bright); background: var(--nx-gold-soft, #f4a93c26); }
    .cttr-star[data-on="true"] svg { fill: var(--cttr-gold-bright); }
    .cttr-star-word { font-family: var(--nx-font-sans); font-size: var(--nx-text-sm); font-weight: 600; }
    .cttr-docs { display: inline-flex; align-items: center; gap: .45rem; color: var(--cttr-accent);
                 font: 600 var(--nx-text-sm) / 1 var(--nx-font-sans); text-decoration: none; }
    .cttr-docs svg { inline-size: 1rem; block-size: 1rem; }
    .cttr-docs:hover { text-decoration: underline; text-underline-offset: 4px; }
    .cttr-docs:focus-visible { outline: 2px solid var(--cttr-accent); outline-offset: 3px; border-radius: 4px; }
    .cttr-term { inline-size: 100%; border-radius: var(--nx-radius-xl); overflow: clip; background: #0a0c17; color: #f7f7fa;
                 box-shadow: 0 24px 48px #0a0c173d; }
    .cttr-bar { display: flex; align-items: center; gap: .5rem; padding: .6rem .9rem; background: #141726;
                border-block-end: 1px solid #2a2d40; }
    .cttr-dot { inline-size: .7rem; aspect-ratio: 1; border-radius: 50%; }
    .cttr-dot[data-r] { background: #ff7a7a; }
    .cttr-dot[data-y] { background: #fbbf24; }
    .cttr-dot[data-g] { background: #10b981; }
    .cttr-tab { margin-inline-start: .35rem; font: 500 .72rem / 1 var(--nx-font-mono); color: #6c7088; }
    .cttr-copybtn { display: inline-flex; align-items: center; gap: .4rem; margin-inline-start: auto; padding: .38rem .65rem;
                    border: none; border-radius: .5rem; background: transparent; cursor: pointer;
                    font: 600 .72rem / 1 var(--nx-font-mono); color: #9a9db3;
                    transition: color .18s ease, background-color .18s ease; }
    .cttr-copybtn svg { inline-size: .85rem; block-size: .85rem; }
    .cttr-copybtn:hover { color: #f7f7fa; background: #1d2031; }
    .cttr-copybtn:focus-visible { outline: 2px solid #7df3ff; outline-offset: 1px; }
    .cttr-copybtn[data-done="true"] { color: #3ddc97; }
    .cttr-copybtn span { display: inline-flex; align-items: center; gap: .4rem; }
    .cttr-screen { min-block-size: 9rem; padding: 1.15rem 1.3rem;
                   font: 500 var(--nx-text-sm) / 1.95 var(--nx-font-mono); }
    .cttr-screen p { margin: 0; overflow-wrap: anywhere; }
    .cttr-p { color: #10b981; margin-inline-end: .15em; }
    .cttr-cmd { color: #f7f7fa; }
    .cttr-caret { display: inline-block; inline-size: .55em; block-size: 1.05em; margin-inline-start: .08em;
                  background: #7df3ff; vertical-align: text-bottom; animation: cttr-blink 1.1s steps(2) infinite; }
    @keyframes cttr-blink { 50% { opacity: 0; } }
    .cttr-out { color: #9a9db3; opacity: 0; translate: 0 4px; transition: opacity .3s ease, translate .3s ease; }
    .cttr-out.cttr-on { opacity: 1; translate: 0 0; }
    .cttr-out b { color: #3ddc97; font-weight: 500; }
    .cttr-out i { font-style: normal; color: #7df3ff; }
    @media (prefers-reduced-motion: reduce) {
        .cttr-caret { animation: none; }
        .cttr-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .cttr-caret { animation: none; opacity: 1; }
    }
</style>

<div class="cttr-root"
    x-data="{
        cmd: 'npm i -g @meridian/cli',
        typed: '',
        done: false,
        copied: false,
        stars: 12840,
        starred: false,
        timers: [],
        locale: '{{ app()->getLocale() }}',
        init() {
            if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.typed = this.cmd;
                this.done = true;
                return;
            }
            this.play();
        },
        play() {
            this.clearAll();
            this.typed = '';
            this.done = false;
            let i = 0;
            const t = setInterval(() => {
                this.typed = this.cmd.slice(0, ++i);
                if (i >= this.cmd.length) {
                    clearInterval(t);
                    this.timers.push(setTimeout(() => {
                        this.done = true;
                        this.timers.push(setTimeout(() => this.play(), 6000));
                    }, 650));
                }
            }, 55);
            this.timers.push(t);
        },
        clearAll() {
            this.timers.forEach((id) => { clearInterval(id); clearTimeout(id); });
            this.timers = [];
        },
        copy() {
            if (navigator.clipboard) { navigator.clipboard.writeText(this.cmd).catch(() => {}); }
            this.copied = true;
            setTimeout(() => { this.copied = false; }, 1800);
        },
        toggleStar() {
            this.starred = !this.starred;
            this.stars += this.starred ? 1 : -1;
        },
        get starsLabel() { return this.stars.toLocaleString(this.locale || 'en'); },
    }">
    <section aria-label="{{ $say('Developer CTA with terminal', 'دعوت به کنش دولوپری با ترمینال') }}">
        <div class="cttr-wrap">
            <div class="cttr-pitch">
                <span class="cttr-badge">{{ \NabuXUI\NabuXUI::icon('command') }}{{ $say('For developers', 'برای دولوپرها') }}</span>
                <h3 class="cttr-title">{{ $say('Terminal to deploy, thirty seconds.', 'از ترمینال تا دِپلوی، ' . $num('30') . ' ثانیه.') }}</h3>
                <p class="cttr-sub">
                    {{ $say('The CLI settles in with one command — your first rollout lands before you write a line of config.', '‏CLI سبک ما با یک دستور جا می‌شود — اولین رِلیز را قبل از نوشتن اولین خط کانفیگ می‌بینی.') }}
                </p>
                <div class="cttr-row">
                    <button type="button" class="cttr-star" x-bind:data-on="starred" :aria-pressed="starred" x-on:click="toggleStar()">
                        {{ \NabuXUI\NabuXUI::icon('star') }}
                        <span x-text="starsLabel"></span>
                        <span class="cttr-star-word">{{ $say('Star on GitHub', 'ستاره در گیت‌هاب') }}</span>
                    </button>
                    <a class="cttr-docs" href="https://cta.gallery" target="_blank" rel="noopener">
                        {{ $say('CLI docs', 'مستندات CLI') }}{{ \NabuXUI\NabuXUI::icon('external-link') }}
                    </a>
                </div>
            </div>

            <div class="cttr-term" dir="ltr" lang="en">
                <div class="cttr-bar">
                    <span class="cttr-dot" data-r aria-hidden="true"></span>
                    <span class="cttr-dot" data-y aria-hidden="true"></span>
                    <span class="cttr-dot" data-g aria-hidden="true"></span>
                    <span class="cttr-tab">meridian — zsh</span>
                    <button type="button" class="cttr-copybtn" x-bind:data-done="copied" x-on:click="copy()"
                        :aria-label="copied ? '{{ $say('Command copied', 'دستور کپی شد') }}' : '{{ $say('Copy the install command', 'کپی دستور نصب') }}'">
                        <span x-show="!copied">{{ \NabuXUI\NabuXUI::icon('copy') }}{{ $say('copy', 'کپی') }}</span>
                        <span x-show="copied" x-cloak>{{ \NabuXUI\NabuXUI::icon('check') }}{{ $say('copied', 'کپی شد') }}</span>
                    </button>
                </div>
                <div class="cttr-screen">
                    <p><span class="cttr-p">$</span><span class="cttr-cmd" x-text="typed"></span><span class="cttr-caret" aria-hidden="true"></span></p>
                    <p class="cttr-out" x-bind:class="{ 'cttr-on': done }"><b>✓</b> meridian 2.4.1 — linked to the Frankfurt edge</p>
                    <p class="cttr-out" x-bind:class="{ 'cttr-on': done }"><i>›</i> run “meridian deploy” to ship your first release</p>
                </div>
            </div>
        </div>
    </section>
</div>
