{{--
    Material 3 buttons as a sign-in sheet: the five official variants with a
    ripple that boils from the pointer coordinates (from centre on keyboard),
    three icon buttons and the official disabled opacities.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3btn-root {
        --m3btn-primary: #6750A4; --m3btn-on-primary: #FFFFFF;
        --m3btn-primary-container: #EADDFF; --m3btn-on-primary-container: #21005D;
        --m3btn-secondary-container: #E8DEF8; --m3btn-on-secondary-container: #1D192B;
        --m3btn-surface: #FEF7FF; --m3btn-surface-container: #F3EDF7;
        --m3btn-surface-container-high: #ECE6F0; --m3btn-surface-container-highest: #E6E0E9;
        --m3btn-on-surface: #1D1B20; --m3btn-on-surface-variant: #49454F;
        --m3btn-outline: #79747E; --m3btn-outline-variant: #CAC4D0;
        --m3btn-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3btn-root {
        --m3btn-primary: #D0BCFF; --m3btn-on-primary: #381E72;
        --m3btn-primary-container: #4F378B; --m3btn-on-primary-container: #EADDFF;
        --m3btn-secondary-container: #4A4458; --m3btn-on-secondary-container: #E8DEF8;
        --m3btn-surface: #141218; --m3btn-surface-container: #211F26;
        --m3btn-surface-container-high: #2B2930; --m3btn-surface-container-highest: #36343B;
        --m3btn-on-surface: #E6E0E9; --m3btn-on-surface-variant: #CAC4D0;
        --m3btn-outline: #938F99; --m3btn-outline-variant: #49454F;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3btn-root {
            --m3btn-primary: #D0BCFF; --m3btn-on-primary: #381E72;
            --m3btn-primary-container: #4F378B; --m3btn-on-primary-container: #EADDFF;
            --m3btn-secondary-container: #4A4458; --m3btn-on-secondary-container: #E8DEF8;
            --m3btn-surface: #141218; --m3btn-surface-container: #211F26;
            --m3btn-surface-container-high: #2B2930; --m3btn-surface-container-highest: #36343B;
            --m3btn-on-surface: #E6E0E9; --m3btn-on-surface-variant: #CAC4D0;
            --m3btn-outline: #938F99; --m3btn-outline-variant: #49454F;
        }
    }
    .m3btn-sheet { inline-size: min(100%, 22rem); padding: 1.5rem 1.25rem 1.75rem; border-radius: 1.75rem; background: var(--m3btn-surface-container); display: grid; gap: 1rem; }
    .m3btn-brand { display: flex; align-items: center; gap: .75rem; }
    .m3btn-brand i { display: grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1; border-radius: .875rem; background: var(--m3btn-primary-container); color: var(--m3btn-on-primary-container); }
    .m3btn-brand b { display: block; font: 600 .95rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3btn-on-surface); }
    .m3btn-brand small { display: block; font: 400 .78rem/1.4 Roboto, system-ui, sans-serif; color: var(--m3btn-on-surface-variant); }
    .m3btn-field { position: relative; }
    .m3btn-field label { position: absolute; inset-block-start: -.5rem; inset-inline-start: .75rem; padding-inline: .3rem; font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--m3btn-on-surface-variant); background: var(--m3btn-surface-container); border-radius: 999px; }
    .m3btn-field input { inline-size: 100%; block-size: 3.25rem; padding-inline: 1rem; border: 1px solid var(--m3btn-outline); border-radius: .5rem; background: transparent; color: var(--m3btn-on-surface); font: 400 .9rem Roboto, system-ui, sans-serif; }
    .m3btn-field input::placeholder { color: color-mix(in srgb, var(--m3btn-on-surface-variant) 75%, transparent); }
    .m3btn-field input:focus-visible { outline: 2px solid var(--m3btn-primary); outline-offset: 1px; }
    .m3btn-actions { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; }
    .m3btn, .m3btn-icon { position: relative; overflow: clip; display: inline-flex; align-items: center; justify-content: center; gap: .5rem; border: none; cursor: pointer; -webkit-tap-highlight-color: transparent; font-family: Roboto, system-ui, sans-serif; }
    .m3btn { block-size: 2.5rem; padding-inline: 1.5rem; border-radius: 999px; font: 500 .875rem/1 Roboto, system-ui, sans-serif; letter-spacing: .1px; }
    .m3btn-icon { inline-size: 2.5rem; aspect-ratio: 1; padding: 0; border-radius: 50%; color: var(--m3btn-on-surface-variant); background: transparent; }
    .m3btn::before, .m3btn-icon::before { content: ''; position: absolute; inset: 0; background: currentColor; opacity: 0; transition: opacity .15s var(--m3btn-ease); pointer-events: none; }
    .m3btn:hover::before, .m3btn-icon:hover::before { opacity: .08; }
    .m3btn:active::before, .m3btn-icon:active::before { opacity: .12; }
    .m3btn:focus-visible, .m3btn-icon:focus-visible { outline: 2px solid var(--m3btn-primary); outline-offset: 2px; }
    .m3btn[data-variant='filled'] { background: var(--m3btn-primary); color: var(--m3btn-on-primary); }
    .m3btn[data-variant='tonal'] { background: var(--m3btn-secondary-container); color: var(--m3btn-on-secondary-container); }
    .m3btn[data-variant='elevated'] { background: var(--m3btn-surface-container); color: var(--m3btn-primary); box-shadow: 0 1px 2px rgba(0, 0, 0, .3), 0 1px 3px 1px rgba(0, 0, 0, .15); }
    .m3btn[data-variant='outlined'] { background: transparent; border: 1px solid var(--m3btn-outline); color: var(--m3btn-primary); }
    .m3btn[data-variant='text'] { background: transparent; color: var(--m3btn-primary); padding-inline: .875rem; }
    .m3btn-icon[data-variant='filled'] { background: var(--m3btn-primary); color: var(--m3btn-on-primary); }
    .m3btn-icon[data-variant='tonal'] { background: var(--m3btn-secondary-container); color: var(--m3btn-on-secondary-container); }
    .m3btn:disabled, .m3btn-icon:disabled { cursor: not-allowed; box-shadow: none; }
    .m3btn:disabled { color: color-mix(in srgb, var(--m3btn-on-surface) 38%, transparent); background: color-mix(in srgb, var(--m3btn-on-surface) 12%, transparent); border-color: transparent; }
    .m3btn[data-variant='outlined']:disabled, .m3btn[data-variant='text']:disabled { background: transparent; }
    .m3btn[data-variant='outlined']:disabled { border-color: color-mix(in srgb, var(--m3btn-on-surface) 12%, transparent); }
    .m3btn-icon:disabled { color: color-mix(in srgb, var(--m3btn-on-surface) 38%, transparent); background: color-mix(in srgb, var(--m3btn-on-surface) 12%, transparent); }
    .m3btn-wave { position: absolute; border-radius: 50%; background: currentColor; opacity: .18; pointer-events: none; translate: 0 0; animation: m3btn-boil .5s ease-out forwards; }
    @keyframes m3btn-boil { from { scale: 0; opacity: .18; } to { scale: 1; opacity: 0; } }
    .m3btn-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .m3btn-spec-row { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .m3btn-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .m3btn-spec-cell > small { font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); letter-spacing: .2px; }
    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3btn-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3btn-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column reads as cut off; let this page's table and snippet
       wrap so nothing is read as cut off. */
    @media (max-width: 480px) {
        .pg:has(.m3btn-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.m3btn-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3btn-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<div class="m3btn-root"
    x-data="{
        wave(b, x, y) {
            const r = b.getBoundingClientRect();
            const d = Math.max(r.width, r.height) * 2.2;
            const s = document.createElement('span');
            s.className = 'm3btn-wave';
            s.style.inlineSize = s.style.blockSize = d + 'px';
            s.style.left = (x - r.left - d / 2) + 'px';
            s.style.top = (y - r.top - d / 2) + 'px';
            b.appendChild(s);
            s.addEventListener('animationend', () => s.remove());
        },
        press(e) {
            const b = e.target.closest('button');
            if (!b || b.disabled) return;
            this.wave(b, e.clientX, e.clientY);
        },
        key(e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            const b = e.target.closest('button');
            if (!b || b.disabled) return;
            const r = b.getBoundingClientRect();
            this.wave(b, r.left + r.width / 2, r.top + r.height / 2);
        },
    }"
    x-on:pointerdown="press($event)"
    x-on:keydown="key($event)">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Five buttons, one ripple', 'پنج دکمه، یک موج') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Tap anywhere on a button — the ripple boils from your finger and fades in 500 ms. Focus one and press Enter to see it rise from the centre.', 'هر جای دکمه را لمس کنید — موج از نقطهٔ انگشت می‌جوشد و در ۵۰۰ میلی‌ثانیه محو می‌شود. با Tab بروید و Enter بزنید تا موج از مرکز بلند شود.') }}
            </p>
        </div>

        <div class="m3btn-sheet" role="group" aria-label="{{ $say('Sign in', 'ورود به حساب') }}">
            <div class="m3btn-brand">
                <i aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg></i>
                <span>
                    <b>{{ $say('Nabu Music', 'نابو موزیک') }}</b>
                    <small>{{ $say('Sync your library across devices', 'هم‌گام‌سازی کتابخانه میان دستگاه‌ها') }}</small>
                </span>
            </div>
            <div class="m3btn-field">
                <label for="m3btn-email">{{ $say('Email address', 'نشانی ایمیل') }}</label>
                <input id="m3btn-email" type="email" placeholder="sara@nabu.example" autocomplete="off">
            </div>
            <div class="m3btn-field">
                <label for="m3btn-pass">{{ $say('Password', 'گذرواژه') }}</label>
                <input id="m3btn-pass" type="password" placeholder="••••••••" autocomplete="off">
            </div>
            <div class="m3btn-actions">
                <button type="button" class="m3btn" data-variant="filled">{{ $say('Sign in', 'ورود به حساب') }}</button>
                <button type="button" class="m3btn" data-variant="tonal">{{ $say('Save draft', 'ذخیرهٔ پیش‌نویس') }}</button>
                <button type="button" class="m3btn" data-variant="elevated">{{ $say('Remind me', 'بعداً یادآوری کن') }}</button>
                <button type="button" class="m3btn" data-variant="outlined">{{ $say('Later', 'بعداً') }}</button>
                <button type="button" class="m3btn" data-variant="text">{{ $say('Skip', 'رد کردن') }}</button>
            </div>
            <div class="m3btn-actions" role="group" aria-label="{{ $say('Icon buttons', 'دکمه‌های آیکنی') }}">
                <button type="button" class="m3btn-icon" data-variant="standard" aria-label="{{ $say('Favourite', 'علاقه‌مندی') }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21C7 16.5 3 13.3 3 9a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 4.3-4 7.5-9 12z"/></svg></button>
                <button type="button" class="m3btn-icon" data-variant="filled" aria-label="{{ $say('Continue', 'ادامه') }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg></button>
                <button type="button" class="m3btn-icon" data-variant="tonal" aria-label="{{ $say('Share', 'اشتراک‌گذاری') }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 10.6l6.8-4M8.6 13.4l6.8 4"/></svg></button>
            </div>
            <div class="m3btn-actions" role="group" aria-label="{{ $say('Disabled state', 'حالت غیرفعال') }}">
                <button type="button" class="m3btn" data-variant="filled" disabled>{{ $say('Sign in', 'ورود به حساب') }}</button>
                <button type="button" class="m3btn" data-variant="tonal" disabled>{{ $say('Save draft', 'ذخیرهٔ پیش‌نویس') }}</button>
                <button type="button" class="m3btn" data-variant="outlined" disabled>{{ $say('Later', 'بعداً') }}</button>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('All at 40 px tall, fully rounded, with the official disabled tokens — label at 38% and surface at 12% of the colour.', 'همه ۴۰ پیکسل بلند و تمام‌گرد، با توکن‌های رسمی غیرفعال — برچسب با ۳۸٪ و سطح با ۱۲٪ رنگ.') }}
        </p>
    </div>
    <div class="m3btn-root" style="inline-size: 100%" aria-hidden="false"
        x-data x-on:pointerdown="press($event)" x-on:keydown="key($event)">
        <div class="m3btn-spec-row">
            <div class="m3btn-spec-cell"><button type="button" class="m3btn" data-variant="filled">{{ $say('Filled', 'پر') }}</button><small>filled</small></div>
            <div class="m3btn-spec-cell"><button type="button" class="m3btn" data-variant="tonal">{{ $say('Tonal', 'تونال') }}</button><small>tonal</small></div>
            <div class="m3btn-spec-cell"><button type="button" class="m3btn" data-variant="elevated">{{ $say('Elevated', 'برجسته') }}</button><small>elevated</small></div>
            <div class="m3btn-spec-cell"><button type="button" class="m3btn" data-variant="outlined">{{ $say('Outlined', 'خط‌دار') }}</button><small>outlined</small></div>
            <div class="m3btn-spec-cell"><button type="button" class="m3btn" data-variant="text">{{ $say('Text', 'متنی') }}</button><small>text</small></div>
            <div class="m3btn-spec-cell"><button type="button" class="m3btn-icon" data-variant="standard" aria-label="Standard"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg></button><small>standard</small></div>
            <div class="m3btn-spec-cell"><button type="button" class="m3btn-icon" data-variant="filled" aria-label="Filled"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg></button><small>filled</small></div>
            <div class="m3btn-spec-cell"><button type="button" class="m3btn-icon" data-variant="tonal" aria-label="Tonal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></button><small>tonal</small></div>
        </div>
    </div>
</section>
