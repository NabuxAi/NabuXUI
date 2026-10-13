{{--
    The newsletter footer: a dark, dotted-ground stage where the signup form
    is the hero — big headline, live-validated email field, a success note
    that takes the form's place — with just two sparse link columns and a
    social row underneath.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .nlf-root {
        --nlf-bg: #0B1120; --nlf-panel: #131C31; --nlf-border: #26334D;
        --nlf-text: #E2E8F0; --nlf-muted: #7C8DB0; --nlf-link: #B6C2D9;
        --nlf-accent: #38BDF8; --nlf-accent-ink: #04121F;
        --nlf-error: #FCA5A5; --nlf-ok: #6EE7B7;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--nlf-text);
    }
    html[data-theme="dark"] .nlf-root { --nlf-bg: #060B16; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .nlf-root { --nlf-bg: #060B16; }
    }
    .nlf-foot { position: relative; isolation: isolate; overflow: clip;
                padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1rem, 4vw, 2.5rem) 2rem;
                background-color: var(--nlf-bg);
                background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, .13) 1px, transparent 1.5px);
                background-size: 22px 22px; }
    .nlf-foot::before { content: ''; position: absolute; inset-block-start: -30%; inset-inline: 0; z-index: -1;
                        block-size: 70%; pointer-events: none;
                        background: radial-gradient(60% 100% at 50% 0%, rgba(56, 189, 248, .16), transparent 70%); }
    .nlf-inner { display: grid; gap: 1rem; max-inline-size: 46rem; margin-inline: auto; text-align: center; }
    .nlf-eyebrow { display: inline-flex; align-items: center; gap: .5rem; justify-self: center;
                   padding: .3rem .85rem; font-size: .75rem; font-weight: 600; letter-spacing: .04em;
                   color: var(--nlf-accent); border: 1px solid var(--nlf-border); border-radius: 999px;
                   background: color-mix(in oklab, var(--nlf-panel) 70%, transparent); }
    .nlf-eyebrow i { inline-size: .4rem; aspect-ratio: 1; border-radius: 50%; background: var(--nlf-accent);
                     animation: nlf-pulse 2.2s ease-in-out infinite; }
    .nlf-title { margin: 0; font-size: clamp(1.75rem, 4.5vw, 3rem); font-weight: 800; line-height: 1.15;
                 letter-spacing: -.02em; text-wrap: balance; }
    .nlf-sub { margin: 0; color: var(--nlf-muted); font-size: .95rem; text-wrap: pretty; }
    .nlf-form { display: flex; flex-wrap: wrap; gap: .6rem; justify-content: center;
                margin-block-start: .75rem; }
    .nlf-field { flex: 1 1 15rem; display: grid; gap: .35rem; text-align: start; }
    .nlf-field input { inline-size: 100%; block-size: 3rem; padding-inline: 1rem; color: var(--nlf-text);
                       font: inherit; background: var(--nlf-panel); border: 1px solid var(--nlf-border);
                       border-radius: .8rem; transition: border-color .15s ease, box-shadow .15s ease; }
    .nlf-field input::placeholder { color: var(--nlf-muted); }
    .nlf-field input:focus-visible { outline: none; border-color: var(--nlf-accent);
                                     box-shadow: 0 0 0 3px rgba(56, 189, 248, .25); }
    .nlf-field[data-invalid="true"] input { border-color: var(--nlf-error); }
    .nlf-error { margin: 0; font-size: .8rem; color: var(--nlf-error); }
    .nlf-join { block-size: 3rem; padding-inline: 1.6rem; font: 700 .9rem/1 inherit; cursor: pointer;
                color: var(--nlf-accent-ink); background: var(--nlf-accent); border: none; border-radius: .8rem;
                transition: filter .15s ease, transform .15s ease; }
    .nlf-join:hover { filter: brightness(1.1); }
    .nlf-join:active { transform: scale(.97); }
    .nlf-join:focus-visible { outline: 2px solid var(--nlf-text); outline-offset: 2px; }
    .nlf-done { display: flex; align-items: center; justify-content: center; gap: .6rem;
                margin: .75rem 0 0; padding: .9rem 1.25rem; color: var(--nlf-ok); font-weight: 600;
                border: 1px solid color-mix(in oklab, var(--nlf-ok) 40%, transparent); border-radius: .8rem;
                background: color-mix(in oklab, var(--nlf-ok) 10%, transparent);
                animation: nlf-rise .45s ease both; }
    .nlf-done svg { inline-size: 1.15rem; block-size: 1.15rem; flex: none; stroke: currentColor;
                    stroke-width: 2.4; fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .nlf-fine { margin: .5rem 0 0; font-size: .72rem; color: var(--nlf-muted); }
    .nlf-bottom { display: flex; flex-wrap: wrap; gap: 2rem 3rem; justify-content: space-between;
                  align-items: flex-start; max-inline-size: 72rem; margin-block-start: clamp(2.5rem, 6vw, 4rem);
                  margin-inline: auto; padding-block-start: 1.5rem; border-block-start: 1px solid var(--nlf-border); }
    .nlf-cols { display: flex; flex-wrap: wrap; gap: 3rem; }
    .nlf-cols h4 { margin: 0 0 .6rem; font-size: .7rem; font-weight: 600; letter-spacing: .08em;
                   text-transform: uppercase; color: var(--nlf-muted); }
    .nlf-cols a { display: block; padding-block: .225rem; font-size: .875rem; color: var(--nlf-link);
                  text-decoration: none; transition: color .15s ease; }
    .nlf-cols a:hover { color: #fff; text-decoration: underline; }
    .nlf-cols a:focus-visible { outline: 2px solid var(--nlf-accent); outline-offset: 2px; border-radius: 2px; }
    .nlf-social { display: flex; gap: .5rem; }
    .nlf-social a { display: grid; place-items: center; min-inline-size: 2.1rem; block-size: 2.1rem;
                    padding-inline: .55rem; font-size: .78rem; font-weight: 600; color: var(--nlf-link);
                    border: 1px solid var(--nlf-border); border-radius: 999px; text-decoration: none;
                    transition: color .15s ease, border-color .15s ease, background-color .15s ease; }
    .nlf-social a:hover { color: #fff; border-color: var(--nlf-accent);
                          background: color-mix(in oklab, var(--nlf-accent) 14%, transparent); }
    .nlf-social a:focus-visible { outline: 2px solid var(--nlf-accent); outline-offset: 2px; }
    @keyframes nlf-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
    @keyframes nlf-rise { from { opacity: 0; translate: 0 .5rem; } to { opacity: 1; translate: 0 0; } }
    @media (prefers-reduced-motion: reduce) {
        .nlf-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important;
                      transition-duration: .01ms !important; }
        .nlf-eyebrow i { animation: none; }
    }
</style>

<div class="nlf-root" x-data="{
    email: '',
    error: '',
    done: false,
    check() {
        return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(this.email.trim());
    },
    submit() {
        this.error = this.check() ? '' : this.email.trim() === ''
            ? '{{ $say('Your email, first', 'اول ایمیلت را بنویس') }}'
            : '{{ $say('That address doesn’t look right', 'این نشانی درست به نظر نمی‌رسد') }}';
        this.done = !this.error;
    },
}">
    <section class="pg-box" style="padding: 0; overflow: clip">
        <footer class="nlf-foot">
            <div class="nlf-inner">
                <span class="nlf-eyebrow"><i aria-hidden="true"></i>{{ $say('The Meridian letter', 'نامهٔ مریدین') }}</span>
                <h3 class="nlf-title">
                    {{ $say('Ship notes, straight to your inbox.', 'یادداشت‌های انتشار، مستقیم در صندوق ایمیل.') }}
                </h3>
                <p class="nlf-sub">
                    {{ $say('One issue a month: release deep-dives, flags we shipped and the postmortems we published. Read by ' . $num('12,400') . ' product teams.', 'ماهی یک شماره: کاوش عمیق انتشار‌ها، فلگ‌هایی که منتشر کردیم و بازبینی‌هایی که نوشتیم. ' . $num('۱۲٬۴۰۰') . ' تیم محصول می‌خوانندش.') }}
                </p>

                <form class="nlf-form" x-show="!done" x-on:submit.prevent="submit()" novalidate>
                    <label class="nlf-field" x-bind:data-invalid="error !== ''">
                        <input type="email" x-model="email" autocomplete="email"
                            placeholder="{{ $say('you@studio.com', 'you@studio.com') }}"
                            :aria-invalid="error !== ''"
                            aria-label="{{ $say('Email address', 'نشانی ایمیل') }}"
                            x-on:input="error = ''">
                        <span class="nlf-error" x-show="error" x-text="error" role="alert" x-cloak></span>
                    </label>
                    <button type="submit" class="nlf-join">{{ $say('Subscribe', 'عضویت') }}</button>
                </form>

                <p class="nlf-done" x-show="done" role="status" x-cloak>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                    {{ $say('You’re on the list — the next issue lands on the first Tuesday.', 'ثبت شدی — شمارهٔ بعدی اولین سه‌شنبهٔ ماه می‌رسد.') }}
                    <button type="button" class="nlf-fine" style="all: unset; cursor: pointer; text-decoration: underline"
                        x-on:click="done = false; email = ''">{{ $say('Use another address', 'با نشانی دیگر') }}</button>
                </p>
                <p class="nlf-fine" x-show="!done">
                    {{ $say('No spam, unsubscribe in one click. See our privacy note.', 'بدون هرزنامه، لغو عضویت با یک کلیک. یادداشت حریم خصوصی ما را ببین.') }}
                </p>
            </div>

            <div class="nlf-bottom">
                <nav class="nlf-cols" aria-label="{{ $say('Footer', 'فوتر') }}">
                    <div>
                        <h4>{{ $say('Product', 'محصول') }}</h4>
                        <a href="#flags">{{ $say('Feature flags', 'فیچر فلگ‌ها') }}</a>
                        <a href="#pricing">{{ $say('Pricing', 'قیمت‌گذاری') }}</a>
                        <a href="#changelog">{{ $say('Changelog', 'تغییرات') }}</a>
                    </div>
                    <div>
                        <h4>{{ $say('Company', 'شرکت') }}</h4>
                        <a href="#about">{{ $say('About', 'دربارهٔ ما') }}</a>
                        <a href="#careers">{{ $say('Careers', 'فرصت‌های شغلی') }}</a>
                        <a href="#contact">{{ $say('Contact', 'تماس') }}</a>
                    </div>
                </nav>
                <div class="nlf-social">
                    <a href="#x">X</a>
                    <a href="#github">GitHub</a>
                    <a href="#linkedin">LinkedIn</a>
                    <a href="#rss">RSS</a>
                </div>
            </div>
        </footer>
    </section>
</div>
