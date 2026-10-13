{{--
    The auth flow's real scenario: a Northwind sign-in — login form, then the
    six-digit code whose boxes focus themselves, accept a whole paste at once
    and hand Backspace to the previous box; a 30s resend timer that clears
    itself on teardown; a wrong code shakes the frame; the two-step
    forgot-password branch; and the welcome screen at the end.
    Alpine state only; the interval is cleaned in destroy().
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .flau-root {
        --flau-accent: #4f46e5; --flau-accent-soft: #eef0fe; --flau-on-accent: #fff;
        --flau-text: #191b2a; --flau-muted: #666b83;
        --flau-border: #e4e6ef; --flau-surface: #fff; --flau-layer: #f5f6fb;
        --flau-danger: #c62a2f; --flau-danger-border: #f5b9bb; --flau-danger-soft: #fdecec;
        --flau-ok: #0f8a43; --flau-ok-soft: #e7f6ec;
        display: grid; gap: 1.25rem; justify-items: center;
    }
    html[data-theme="dark"] .flau-root {
        --flau-accent: #8e94f8; --flau-accent-soft: #262a4d; --flau-on-accent: #14152b;
        --flau-text: #ecedf6; --flau-muted: #a6abc4;
        --flau-border: #34364e; --flau-surface: #1b1c2e; --flau-layer: #222439;
        --flau-danger: #f0868b; --flau-danger-border: #5c3138; --flau-danger-soft: #3a2226;
        --flau-ok: #5bd08d; --flau-ok-soft: #1d3a2a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flau-root {
            --flau-accent: #8e94f8; --flau-accent-soft: #262a4d; --flau-on-accent: #14152b;
            --flau-text: #ecedf6; --flau-muted: #a6abc4;
            --flau-border: #34364e; --flau-surface: #1b1c2e; --flau-layer: #222439;
            --flau-danger: #f0868b; --flau-danger-border: #5c3138; --flau-danger-soft: #3a2226;
            --flau-ok: #5bd08d; --flau-ok-soft: #1d3a2a;
        }
    }
    .flau-card { inline-size: min(100%, 24rem); display: grid; gap: 1.15rem; padding: 1.6rem;
                 border: 1px solid var(--flau-border); border-radius: 1.4rem; background: var(--flau-surface);
                 color: var(--flau-text); box-shadow: 0 18px 40px -28px rgb(20 22 48 / .35);
                 animation: flau-in .3s cubic-bezier(.2, 0, 0, 1); }
    @keyframes flau-in { from { opacity: 0; translate: 0 10px; } }
    .flau-brand { display: flex; align-items: center; gap: .6rem; }
    .flau-brand i { display: grid; place-items: center; inline-size: 2.3rem; aspect-ratio: 1;
                    border-radius: .8rem; background: var(--flau-accent-soft); color: var(--flau-accent); }
    .flau-brand i svg { inline-size: 1.15rem; }
    .flau-brand b { font: 700 1rem / 1.2 var(--nx-font-display, inherit); }
    .flau-card h4 { margin: 0; font: 700 var(--nx-text-lg, 1.05rem) / 1.4 var(--nx-font-display, inherit); }
    .flau-card > p.lead { margin: 0; font-size: .85rem; color: var(--flau-muted); line-height: 1.7; }
    .flau-fields { display: grid; gap: .8rem; }
    .flau-field { display: grid; gap: .3rem; font-size: .78rem; font-weight: 600; color: var(--flau-muted); }
    .flau-field input { min-block-size: 2.7rem; padding-inline: .85rem; border: 1px solid var(--flau-border);
                        border-radius: .75rem; background: var(--flau-surface); color: var(--flau-text);
                        font: inherit; font-weight: 400; }
    .flau-field input:focus-visible { outline: 2px solid var(--flau-accent); outline-offset: 1px; }
    .flau-field input[data-invalid] { border-color: var(--flau-danger); box-shadow: inset 0 0 0 1px var(--flau-danger); }
    .flau-err { display: flex; align-items: center; gap: .3rem; color: var(--flau-danger); font-size: .73rem; font-weight: 500; }
    .flau-err svg { inline-size: .85rem; flex: none; }
    .flau-boxes { display: flex; gap: .5rem; justify-content: center; padding-block: .35rem; }
    .flau-boxes input { inline-size: 2.8rem; block-size: 3.4rem; border: 1px solid var(--flau-border);
                        border-radius: .85rem; background: var(--flau-surface); color: var(--flau-text);
                        font: 700 1.25rem / 1 ui-monospace, Menlo, monospace; text-align: center;
                        transition: border-color .15s ease, box-shadow .15s ease, translate .15s ease, background-color .15s ease; }
    .flau-boxes input:focus { outline: none; border-color: var(--flau-accent); box-shadow: 0 0 0 3px var(--flau-accent-soft);
                              translate: 0 -2px; }
    .flau-boxes[data-bad] input { border-color: var(--flau-danger); }
    .flau-shake { animation: flau-shake .38s ease; }
    @keyframes flau-shake { 20%, 60% { translate: -8px 0; } 40%, 80% { translate: 8px 0; } }
    .flau-code-hint { display: inline-flex; align-items: center; gap: .45rem; justify-self: center;
                      padding: .35rem .8rem; border-radius: 999px; background: var(--flau-layer);
                      font-size: .75rem; color: var(--flau-muted); }
    .flau-code-hint b { font-family: ui-monospace, Menlo, monospace; letter-spacing: .18em; color: var(--flau-text); }
    .flau-errline { display: flex; align-items: center; justify-content: center; gap: .4rem; margin: 0;
                    color: var(--flau-danger); font-size: .8rem; font-weight: 600; }
    .flau-errline svg { inline-size: .95rem; }
    .flau-actions { display: grid; gap: .55rem; }
    .flau-btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-block-size: 2.75rem;
                padding-inline: 1.3rem; border: none; border-radius: 999px; background: var(--flau-accent);
                color: var(--flau-on-accent); font: 600 .88rem / 1 inherit; cursor: pointer;
                transition: filter .18s ease, opacity .18s ease; }
    .flau-btn:hover:not(:disabled) { filter: brightness(1.08); }
    .flau-btn:disabled { opacity: .45; cursor: not-allowed; }
    .flau-btn:focus-visible { outline: 2px solid var(--flau-accent); outline-offset: 2px; }
    .flau-btn svg { inline-size: 1rem; }
    html[dir="rtl"] .flau-btn svg[data-arrow] { rotate: 180deg; }
    .flau-link { border: none; background: none; padding: 0; color: var(--flau-accent); font: 600 .8rem / 1.8 inherit;
                 cursor: pointer; justify-self: center; }
    .flau-link:hover { text-decoration: underline; }
    .flau-link:disabled { color: var(--flau-muted); cursor: default; text-decoration: none; }
    .flau-resend { display: flex; align-items: center; justify-content: center; gap: .4rem; font-size: .78rem; color: var(--flau-muted); }
    .flau-resend b { font-variant-numeric: tabular-nums; }
    .flau-ok { justify-items: center; text-align: center; gap: .8rem; }
    .flau-ok-ring { display: grid; place-items: center; inline-size: 4.5rem; aspect-ratio: 1; border-radius: 50%;
                    background: var(--flau-ok-soft); }
    .flau-ok-ring svg { inline-size: 2.4rem; color: var(--flau-ok); }
    .flau-ok-ring path { stroke-dasharray: 30; stroke-dashoffset: 30; animation: flau-draw .5s ease-out .1s forwards; }
    .flau-ok-ring circle { stroke-dasharray: 76; stroke-dashoffset: 76; animation: flau-draw .6s ease-out forwards; }
    @keyframes flau-draw { to { stroke-dashoffset: 0; } }
    .flau-sent-note { display: flex; align-items: flex-start; gap: .55rem; margin: 0; padding: .75rem .9rem;
                      border: 1px solid var(--flau-ok); border-radius: .8rem; background: var(--flau-ok-soft);
                      color: var(--flau-ok); font-size: .82rem; line-height: 1.6; }
    .flau-sent-note svg { flex: none; inline-size: 1rem; margin-block-start: .12rem; }
    .flau-hint { margin: 0; max-inline-size: 56ch; text-align: center; font-size: .82rem; color: var(--nx-text-muted); }
    @media (max-width: 480px) {
        .flau-card { padding: 1.15rem; border-radius: 1.15rem; }
        .flau-boxes input { inline-size: 2.5rem; block-size: 3.1rem; font-size: 1.1rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .flau-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .flau-shake { animation: none; }
    }
</style>

<div class="flau-root"
    x-data="{
        fa: {{ $fa ? 'true' : 'false' }},
        stage: 'login',
        email: 'maya@nordvana.io',
        password: '',
        errors: {},
        code: ['', '', '', '', '', ''],
        bad: false,
        shaking: false,
        error: '',
        timer: 30,
        resent: false,
        tick: null,
        init() {
            this.tick = setInterval(() => {
                if (this.stage === 'code' && this.timer > 0) this.timer--;
                if (this.timer === 0) this.resent = false;
            }, 1000);
        },
        destroy() { clearInterval(this.tick); },
        n(v) { const s = String(v); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        boxes() { return [...this.$root.querySelectorAll('.flau-boxes input')]; },
        toCode() {
            this.errors = {};
            if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(this.email)) this.errors.email = '{{ $say('This email does not parse', 'این ایمیل خوانده نمی‌شود') }}';
            if (this.password.length < 6) this.errors.password = '{{ $say('At least 6 characters', 'دست‌کم ۶ نویسه') }}';
            if (Object.keys(this.errors).length) return;
            this.stage = 'code'; this.timer = 30;
            this.$nextTick(() => this.boxes()[0]?.focus());
        },
        type(i, e) {
            const d = e.target.value.replace(/\D/g, '').slice(-1);
            this.code[i] = d; e.target.value = d;
            if (this.bad) { this.bad = false; this.error = ''; }
            if (d && i < 5) this.boxes()[i + 1]?.focus();
        },
        back(i, e) { if (!e.target.value && i > 0) this.boxes()[i - 1]?.focus(); },
        paste(e) {
            const d = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
            if (!d) return;
            e.preventDefault();
            this.code = d.padEnd(6, '').slice(0, 6).split('');
            this.boxes()[Math.min(5, d.length)]?.focus();
        },
        get digits() { return this.code.join(''); },
        get full() { return this.digits.length === 6 && !this.code.includes(''); },
        verify() {
            if (this.digits !== '284913') {
                this.error = '{{ $say('That code is not right — codes live for 10 minutes', 'کد درست نیست — کدها ۱۰ دقیقه زنده‌اند') }}';
                this.bad = true; this.shaking = true;
                setTimeout(() => { this.shaking = false; }, 420);
                setTimeout(() => { this.code = ['', '', '', '', '', '']; this.boxes()[0]?.focus(); }, 260);
                return;
            }
            this.error = ''; this.bad = false; this.stage = 'ok';
        },
        resend() {
            if (this.timer > 0) return;
            this.timer = 30; this.resent = true; this.error = ''; this.bad = false;
            this.boxes()[0]?.focus();
        },
        sendReset() {
            this.errors = {};
            if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(this.email)) this.errors.reset = '{{ $say('This email does not parse', 'این ایمیل خوانده نمی‌شود') }}';
            if (!Object.keys(this.errors).length) this.stage = 'forgot-sent';
        },
        restart() {
            this.stage = 'login'; this.password = ''; this.code = ['', '', '', '', '', ''];
            this.error = ''; this.bad = false; this.timer = 30; this.resent = false; this.errors = {};
        },
    }">
    <section class="pg-box" style="gap: 1.25rem; justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Two steps, six boxes, one honest timer', 'دو قدم، شش خانه، یک تایمرِ روراست') }}</h3>
            <p style="margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted)">
                {{ $say('The sign-in hands you a six-digit code: every box focuses the next as you type, a pasted code fills all six at once, Backspace walks backwards, the wrong code shakes the frame and clears it, and the resend button sleeps for a real 30-second countdown. Forgot password branches off into its own two steps.', 'ورود، کدی شش‌رقمی به دستتان می‌دهد: هر خانه با تایپ، بعدی را فوکوس می‌کند، کدِ چسبانده‌شده هر شش خانه را یک‌جا پر می‌کند، Backspace به عقب برمی‌گردد، کد نادرست قاب را می‌لرزاند و خالی می‌کند، و دکمهٔ ارسال دوباره تا شمارش معکوسِ واقعی ۳۰ ثانیه‌ای می‌خوابد. فراموشی رمز هم شاخهٔ دوقدمی خودش را دارد.') }}
            </p>
        </div>

        <form class="flau-card" x-show="stage === 'login'" x-on:submit.prevent="toCode()">
            <div class="flau-brand">
                <i aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.4-3 7.6-7 9-4-1.4-7-4.6-7-9V6z"/><path d="M9.5 12l1.8 1.8L15 10"/></svg>
                </i>
                <b>{{ $say('Northwind', 'نورث‌ویند') }}</b>
            </div>
            <div>
                <h4>{{ $say('Welcome back', 'خوش بازگشتید') }}</h4>
                <p class="lead">{{ $say('Sign in and we’ll double-check it’s you with a code.', 'وارد شوید تا با یک کد مطمئن شویم که خودتان هستید.') }}</p>
            </div>
            <div class="flau-fields">
                <label class="flau-field">
                    <span>{{ $say('Email', 'ایمیل') }}</span>
                    <input type="email" x-model="email" dir="ltr" :data-invalid="errors.email ? '' : null" style="text-align: start">
                    <span class="flau-err" x-show="errors.email" x-cloak>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                        <span x-text="errors.email"></span>
                    </span>
                </label>
                <label class="flau-field">
                    <span>{{ $say('Password', 'رمز') }}</span>
                    <input type="password" x-model="password" :data-invalid="errors.password ? '' : null" placeholder="••••••••" dir="ltr" style="text-align: start">
                    <span class="flau-err" x-show="errors.password" x-cloak>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                        <span x-text="errors.password"></span>
                    </span>
                </label>
            </div>
            <div class="flau-actions">
                <button type="submit" class="flau-btn">
                    {{ $say('Continue with code', 'ادامه با کد') }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-arrow aria-hidden="true"><path d="M5 12h14m-6-6l6 6-6 6"/></svg>
                </button>
                <button type="button" class="flau-link" x-on:click="stage = 'forgot-email'; errors = {}">{{ $say('Forgot password?', 'رمز را فراموش کرده‌اید؟') }}</button>
            </div>
        </form>

        <section class="flau-card" x-show="stage === 'code'" x-cloak>
            <div class="flau-brand">
                <i aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2.5"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                </i>
                <b>{{ $say('Northwind', 'نورث‌ویند') }}</b>
            </div>
            <div>
                <h4>{{ $say('Enter your code', 'کد را وارد کنید') }}</h4>
                <p class="lead">
                    {{ $say('Sent to', 'فرستاده‌شده به') }} <span dir="ltr" x-text="email"></span> —
                    {{ $say('type it, or paste all six at once.', 'تایپش کنید، یا هر شش رقم را یک‌جا بچسبانید.') }}
                </p>
            </div>

            <span class="flau-code-hint">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" style="inline-size: .9rem"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                {{ $say('demo code', 'کد دمو') }}: <b dir="ltr">284913</b>
            </span>

            <div class="flau-boxes" :class="shaking ? 'flau-shake' : ''" :data-bad="bad ? '' : null" dir="ltr"
                role="group" aria-label="{{ $say('Six-digit code', 'کد شش‌رقمی') }}">
                <template x-for="(d, i) in code" :key="i">
                    <input type="text" maxlength="2" inputmode="numeric" autocomplete="one-time-code" :value="d"
                        :aria-label="'digit ' + (i + 1)"
                        x-on:input="type(i, $event)" x-on:keydown.backspace="back(i, $event)" x-on:paste="paste($event)">
                </template>
            </div>

            <p class="flau-errline" role="alert" x-show="error" x-cloak>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                <span x-text="error"></span>
            </p>

            <div class="flau-actions">
                <button type="button" class="flau-btn" x-on:click="verify()" :disabled="!full">{{ $say('Verify', 'بررسی') }}</button>
                <span class="flau-resend">
                    <button type="button" class="flau-link" x-on:click="resend()" :disabled="timer > 0"
                        x-text="timer > 0 ? '' : (resent ? '{{ $say('Sent again ✓', 'دوباره فرستاده شد ✓') }}' : '{{ $say('Resend code', 'ارسال دوبارهٔ کد') }}')"></button>
                    <span x-show="timer > 0" x-cloak>
                        {{ $say('resend in', 'ارسال دوباره تا') }} <b x-text="n(timer)"></b> {{ $say('s', 'ثانیه') }}
                    </span>
                </span>
                <button type="button" class="flau-link" x-on:click="stage = 'login'">{{ $say('Back to sign in', 'بازگشت به ورود') }}</button>
            </div>
        </section>

        <section class="flau-card flau-ok" x-show="stage === 'ok'" x-cloak>
            <span class="flau-ok-ring" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="24" cy="24" r="15.9" stroke-width="2"/>
                    <path d="M15.5 24.5l5.5 5.5 11.5-12"/>
                </svg>
            </span>
            <h4>{{ $say('Welcome back, Maya', 'خوش بازگشتی، مایا') }}</h4>
            <p style="margin: 0; color: var(--flau-muted); font-size: .86rem">
                {{ $say('Device trusted — next time we may skip the code.', 'این دستگاه امن شناخته شد — دفعهٔ بعد شاید کد را لازم نداشته باشیم.') }}
            </p>
            <button type="button" class="flau-btn" x-on:click="restart()">{{ $say('Sign out', 'خروج') }}</button>
        </section>

        <form class="flau-card" x-show="stage === 'forgot-email'" x-cloak x-on:submit.prevent="sendReset()">
            <div>
                <h4>{{ $say('Reset your password', 'بازیابی رمز') }}</h4>
                <p class="lead">{{ $say('Step 1 of 2 — tell us the account email and we’ll send a reset link.', 'قدم ۱ از ۲ — ایمیل حساب را بگویید تا پیوند بازیابی بفرستیم.') }}</p>
            </div>
            <label class="flau-field">
                <span>{{ $say('Email', 'ایمیل') }}</span>
                <input type="email" x-model="email" dir="ltr" :data-invalid="errors.reset ? '' : null" style="text-align: start">
                <span class="flau-err" x-show="errors.reset" x-cloak>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3v.01"/></svg>
                    <span x-text="errors.reset"></span>
                </span>
            </label>
            <div class="flau-actions">
                <button type="submit" class="flau-btn">{{ $say('Send reset link', 'فرستادن پیوند بازیابی') }}</button>
                <button type="button" class="flau-link" x-on:click="stage = 'login'; errors = {}">{{ $say('Back to sign in', 'بازگشت به ورود') }}</button>
            </div>
        </form>

        <section class="flau-card" x-show="stage === 'forgot-sent'" x-cloak>
            <p class="flau-sent-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 5-5.5"/></svg>
                <span>{{ $say('<b>Step 2 of 2 — link sent.</b> It lives for 20 minutes; opening it on this device signs you straight back in.', '<b>قدم ۲ از ۲ — پیوند فرستاده شد.</b> ۲۰ دقیقه زنده است؛ بازکردنش روی همین دستگاه شما را مستقیم برمی‌گرداند.') }}</span>
            </p>
            <div class="flau-actions">
                <button type="button" class="flau-btn" x-on:click="stage = 'login'">{{ $say('Back to sign in', 'بازگشت به ورود') }}</button>
            </div>
        </section>

        <p class="flau-hint">{{ $say('No backend here: the code 284913 succeeds, anything else shakes; the countdown is a real interval that tears itself down with the component.', 'اینجا بک‌اندی در کار نیست: کد 284913 قبول می‌شود، هر چیز دیگری می‌لرزاند؛ شمارش معکوس یک اینتروال واقعی است که با تخریب کامپوننت خودش را جمع می‌کند.') }}</p>
    </section>
</div>
