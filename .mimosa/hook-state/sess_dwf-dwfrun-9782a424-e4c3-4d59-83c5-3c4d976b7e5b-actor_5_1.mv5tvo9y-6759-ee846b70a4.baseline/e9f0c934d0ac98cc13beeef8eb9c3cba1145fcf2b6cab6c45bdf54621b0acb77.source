{{--
    The glass hero as a real first screen: a liquid-glass panel floating over
    three slowly drifting gradient blobs. The glass drinks the background —
    blur plus saturation — and catches a lit edge on its border and top
    highlight. Inside, the short sign-in form: email and password with live
    validation (a shake on error), a magic-link submit, and the fine-print
    guarantee row. Success swaps the panel to its confirmation state.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
@endphp
<style>
    .hrg-hero {
        --hrg-bg: #0f122e;
        --hrg-text: #eef1ff; --hrg-muted: #b9c0ea;
        --hrg-accent: #8a94ff;
        position: relative; overflow: clip; isolation: isolate;
        padding: clamp(3.5rem, 8vw, 6.5rem) 1.25rem;
        background: radial-gradient(110% 100% at 50% 0%, #1a1f4d 0%, var(--hrg-bg) 62%);
        color: var(--hrg-text);
        display: grid; gap: 1.5rem; justify-items: center; text-align: center;
    }
    html[data-theme="dark"] .hrg-hero { --hrg-bg: #0b0e24; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hrg-hero { --hrg-bg: #0b0e24; }
    }
    .hrg-blob { position: absolute; z-index: -1; border-radius: 50%; filter: blur(70px);
                background: radial-gradient(circle, var(--hrg-c), transparent 70%);
                opacity: .55; animation: hrg-drift var(--hrg-t, 24s) ease-in-out infinite alternate; }
    .hrg-blob:nth-child(1) { inline-size: 28rem; aspect-ratio: 1; inset-block-start: -8rem; inset-inline-start: 6%; --hrg-c: #6d7cff; --hrg-t: 22s; }
    .hrg-blob:nth-child(2) { inline-size: 24rem; aspect-ratio: 1.3; inset-block-end: -6rem; inset-inline-end: 4%; --hrg-c: #a855f7; --hrg-t: 28s; animation-direction: alternate-reverse; }
    .hrg-blob:nth-child(3) { inline-size: 16rem; aspect-ratio: 1; inset-block-start: 34%; inset-inline-start: 54%; --hrg-c: #22d3ee; --hrg-t: 19s; opacity: .35; }
    @keyframes hrg-drift {
        from { translate: 0 0; scale: 1; }
        to   { translate: var(--hrg-dx, 5rem) var(--hrg-dy, -3rem); scale: 1.22; }
    }
    .hrg-eyebrow { display: grid; gap: .8rem; justify-items: center; }
    .hrg-eyebrow h2 { margin: 0; font: 800 clamp(1.9rem, 4.6vw, 3.1rem) / 1.12 var(--nx-font-display);
                      letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .hrg-eyebrow p { margin: 0; max-inline-size: 40ch; color: var(--hrg-muted); font-size: var(--nx-text-base); text-wrap: pretty; }

    .hrg-panel { position: relative; inline-size: min(100%, 25rem); padding: clamp(1.5rem, 4vw, 2rem);
                 border-radius: 1.75rem; text-align: start;
                 background: color-mix(in oklab, #ffffff 11%, transparent);
                 border: 1px solid color-mix(in oklab, #ffffff 30%, transparent);
                 backdrop-filter: blur(22px) saturate(160%);
                 -webkit-backdrop-filter: blur(22px) saturate(160%);
                 box-shadow: inset 0 1px 0 color-mix(in oklab, #ffffff 34%, transparent),
                             0 1.5rem 3.75rem color-mix(in oklab, #05081c 55%, transparent);
                 display: grid; gap: 1rem; }
    .hrg-panel::after { content: ""; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
                        background: linear-gradient(180deg, color-mix(in oklab, #ffffff 14%, transparent), transparent 28%);
                        opacity: .8; }
    .hrg-brand { display: flex; align-items: center; gap: .55rem; font: 700 var(--nx-text-base)/1 var(--nx-font-display); }
    .hrg-brand svg { inline-size: 1.4rem; aspect-ratio: 1; color: var(--hrg-accent); }
    .hrg-panel h3 { margin: 0; font: 700 var(--nx-text-lg)/1.3 var(--nx-font-display); }
    .hrg-panel > p { margin: 0; font-size: var(--nx-text-sm); color: var(--hrg-muted); }
    .hrg-field { display: grid; gap: .375rem; }
    .hrg-field label { font: 600 .72rem/1 var(--nx-font-mono); letter-spacing: .06em; text-transform: uppercase; color: var(--hrg-muted); }
    .hrg-field small { min-block-size: 1em; font-size: .72rem; color: #ffb4b4; }
    .hrg-input { display: flex; align-items: center; gap: .6rem; padding: .7rem .9rem; border-radius: .9rem;
                 border: 1px solid color-mix(in oklab, #ffffff 26%, transparent);
                 background: color-mix(in oklab, #ffffff 9%, transparent);
                 color: inherit; transition: border-color .2s ease, background-color .2s ease; }
    .hrg-input:focus-within { border-color: color-mix(in oklab, var(--hrg-accent) 75%, transparent);
                              background: color-mix(in oklab, #ffffff 13%, transparent); }
    .hrg-input svg { inline-size: 1rem; aspect-ratio: 1; color: var(--hrg-muted); flex: none; }
    .hrg-input input { inline-size: 100%; border: none; background: transparent; color: inherit;
                       font: 400 .9rem/1.3 inherit; outline: none; }
    .hrg-input input::placeholder { color: color-mix(in oklab, var(--hrg-muted) 70%, transparent); }
    .hrg-input[data-bad] { border-color: #ff8b8b; }
    .hrg-shake { animation: hrg-shake .4s ease; }
    @keyframes hrg-shake { 20% { translate: -.35rem 0; } 60% { translate: .35rem 0; } }
    .hrg-alt { margin: 0; text-align: center; font-size: .78rem; color: var(--hrg-muted); }
    .hrg-alt a { color: var(--hrg-accent); }
    .hrg-fine { display: flex; flex-wrap: wrap; justify-content: center; gap: .3rem 1rem; margin: .125rem 0 0;
                font-size: .72rem; color: color-mix(in oklab, var(--hrg-muted) 85%, transparent); }
    .hrg-fine span { display: inline-flex; align-items: center; gap: .3rem; }
    .hrg-fine svg { inline-size: .75rem; aspect-ratio: 1; color: color-mix(in oklab, var(--hrg-accent) 80%, white); }
    .hrg-done { display: grid; gap: .75rem; justify-items: start; }
    .hrg-done svg { inline-size: 2.25rem; aspect-ratio: 1; color: #4ade80; }
    @media (prefers-reduced-motion: reduce) {
        .hrg-blob { animation: none; }
        .hrg-shake { animation-duration: .01ms; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="hrg-hero">
        <i class="hrg-blob" aria-hidden="true"></i>
        <i class="hrg-blob" aria-hidden="true"></i>
        <i class="hrg-blob" aria-hidden="true"></i>

        <div class="hrg-eyebrow">
            <x-nx::badge tone="info">{{ $say('Asterly for teams', 'استرلی برای تیم‌ها') }}</x-nx::badge>
            <h2>{{ $say('Sign in to the calm console', 'ورود به کنسولِ آرام') }}</h2>
            <p>{{ $say('One glass pane between you and every rollout — no passwords to remember, just a magic link.', 'یک پنل شیشه‌ای بین تو و همهٔ انتشارها — بدون رمزهایی که به‌خاطر بسپاری، فقط یک لینک جادویی.') }}</p>
        </div>

        <div class="hrg-panel"
            x-data="{
                email: '', password: '', sent: false, shakeEmail: false, shakePass: false,
                get badEmail() { return this.email.length > 0 && !this.email.includes('@') },
                get badPass() { return this.password.length > 0 && this.password.length < 6 },
                submit() {
                    const okEmail = this.email.includes('@');
                    const okPass = this.password.length >= 6;
                    if (!okEmail) { this.shakeEmail = true; setTimeout(() => this.shakeEmail = false, 450); }
                    if (!okPass) { this.shakePass = true; setTimeout(() => this.shakePass = false, 450); }
                    if (okEmail && okPass) this.sent = true;
                },
            }">
            <template x-if="!sent">
                <form style="display: grid; gap: 1rem" x-on:submit.prevent="submit" novalidate>
                    <span class="hrg-brand">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                            <path d="M12 3v18M3 12h18M6 6l12 12M18 6 6 18" />
                        </svg>
                        Asterly
                    </span>
                    <div>
                        <h3>{{ $say('Create your workspace', 'ورک‌اسپیس‌ات را بساز') }}</h3>
                        <p>{{ $say('Free for 14 days — your team, your regions.', '۱۴ روز رایگان — تیم خودت، ناحیه‌های خودت.') }}</p>
                    </div>
                    <div class="hrg-field">
                        <label for="hrg-email">{{ $say('Work email', 'ایمیل کاری') }}</label>
                        <div class="hrg-input" :class="shakeEmail && 'hrg-shake'" :data-bad="badEmail || null">
                            {!! \NabuXUI\NabuXUI::icon('mail') !!}
                            <input id="hrg-email" type="email" autocomplete="email" x-model="email"
                                placeholder="{{ $say('you@team.com', 'you@team.com') }}" dir="ltr">
                        </div>
                        <small x-text="badEmail ? {{ \Illuminate\Support\Js::from($say('That address is missing an @', 'این نشانی یک @ کم دارد'))->toHtml() }} : ''"></small>
                    </div>
                    <div class="hrg-field">
                        <label for="hrg-pass">{{ $say('Password', 'گذرواژه') }}</label>
                        <div class="hrg-input" :class="shakePass && 'hrg-shake'" :data-bad="badPass || null">
                            {!! \NabuXUI\NabuXUI::icon('lock') !!}
                            <input id="hrg-pass" type="password" autocomplete="new-password" x-model="password"
                                placeholder="{{ $say('At least 6 characters', 'حداقل ۶ نویسه') }}" dir="ltr">
                        </div>
                        <small x-text="badPass ? {{ \Illuminate\Support\Js::from($say('Six characters minimum', 'حداقل شش نویسه'))->toHtml() }} : ''"></small>
                    </div>
                    <x-nx::button variant="primary" shape="pill" block icon-end="arrow-right" type="submit">
                        {{ $say('Continue with a magic link', 'ادامه با لینک جادویی') }}
                    </x-nx::button>
                    <p class="hrg-alt">
                        {{ $say('Already onboard?', 'قبلاً عضو شدی؟') }}
                        <a href="#">{{ $say('Sign in instead', 'وارد شو') }}</a>
                    </p>
                    <p class="hrg-fine">
                        <span>{!! \NabuXUI\NabuXUI::icon('shield') !!}{{ $say('SOC 2 Type II', 'SOC 2 نوع دوم') }}</span>
                        <span>{!! \NabuXUI\NabuXUI::icon('check-circle') !!}{{ $say('No credit card', 'بدون کارت بانکی') }}</span>
                        <span>{!! \NabuXUI\NabuXUI::icon('trash') !!}{{ $say('Delete anytime', 'حذف در هر لحظه') }}</span>
                    </p>
                </form>
            </template>
            <template x-if="sent">
                <div class="hrg-done" role="status">
                    {!! \NabuXUI\NabuXUI::icon('check-circle') !!}
                    <h3>{{ $say('Check your inbox', 'ایمیلت را ببین') }}</h3>
                    <p>{{ $say('We sent a magic link that lives for 15 minutes — one tap and your Frankfurt + Singapore regions are waiting.', 'یک لینک جادویی فرستادیم که ۱۵ دقیقه زنده می‌ماند — یک ضربه و ناحیه‌های فرانکفورت و سنگاپورت منتظرند.') }}</p>
                    <x-nx::button variant="secondary" shape="pill" size="sm" x-on:click="sent = false; email = ''; password = ''">
                        {{ $say('Use another address', 'نشانی دیگری بده') }}
                    </x-nx::button>
                </div>
            </template>
        </div>
    </div>
</section>
