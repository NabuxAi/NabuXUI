{{--
    The minimal footer: one quiet line on a hairline rule — © and the name,
    the pulsing green "all systems operational" dot, three text links and a
    small theme switch that flips the page's real data-theme — plus two even
    barer variants of the same line.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .mnf-root {
        --mnf-bg: #FAFAF9; --mnf-border: #E7E5E4; --mnf-hairline: #EDECEB;
        --mnf-text: #57534E; --mnf-strong: #1C1917; --mnf-ok: #10B981;
        --mnf-track: #E7E5E4;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--mnf-text);
        display: grid;
        gap: 1.5rem;
    }
    html[data-theme="dark"] .mnf-root {
        --mnf-bg: #0C0A09; --mnf-border: #292524; --mnf-hairline: #232120;
        --mnf-text: #A8A29E; --mnf-strong: #FAFAF9; --mnf-track: #292524;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mnf-root {
            --mnf-bg: #0C0A09; --mnf-border: #292524; --mnf-hairline: #232120;
            --mnf-text: #A8A29E; --mnf-strong: #FAFAF9; --mnf-track: #292524;
        }
    }
    .mnf-bar { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; align-items: center;
               justify-content: center; padding: 1rem clamp(1rem, 4vw, 1.5rem);
               background: var(--mnf-bg); border-block-start: 1px solid var(--mnf-hairline); }
    .mnf-bar small { font-size: .8rem; color: var(--mnf-text); }
    .mnf-bar a { color: var(--mnf-text); font-size: .8rem; text-decoration: none;
                 transition: color .15s ease; }
    .mnf-bar a:hover { color: var(--mnf-strong); text-decoration: underline; }
    .mnf-bar a:focus-visible { outline: 2px solid var(--mnf-strong); outline-offset: 2px; border-radius: 2px; }
    .mnf-status { display: inline-flex; align-items: center; gap: .45rem; }
    .mnf-status i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: var(--mnf-ok);
                    box-shadow: 0 0 0 0 rgba(16, 185, 129, .45); animation: mnf-pulse 2.2s ease-in-out infinite; }
    .mnf-status:hover i { animation: none; }
    .mnf-links { display: flex; gap: 1rem; }
    .mnf-switch { position: relative; inline-size: 2.1rem; block-size: 1.15rem; cursor: pointer; flex: none;
                  background: var(--mnf-track); border: none; border-radius: 999px;
                  transition: background .2s ease; }
    .mnf-switch::after { content: ''; position: absolute; inset-block-start: .15rem; inset-inline-start: .15rem;
                         inline-size: .85rem; block-size: .85rem; border-radius: 50%; background: #fff;
                         box-shadow: 0 1px 2px rgba(0, 0, 0, .25);
                         transition: inset-inline-start .2s ease; }
    .mnf-switch[aria-checked="true"] { background: var(--mnf-ok); }
    .mnf-switch[aria-checked="true"]::after { inset-inline-start: 1.1rem; }
    .mnf-switch:focus-visible { outline: 2px solid var(--mnf-strong); outline-offset: 2px; }
    .mnf-spec { display: grid; gap: .75rem; justify-items: center; }
    .mnf-spec-row { display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: stretch; justify-content: center; }
    .mnf-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .mnf-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .mnf-spec-cell .mnf-bar { border-radius: .6rem; border: 1px solid var(--mnf-hairline); border-block-start-width: 1px; }
    .mnf-spec-cell .mnf-bar[data-variant="bare"] { justify-content: space-between; }
    @keyframes mnf-pulse {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(16, 185, 129, .4); }
        50% { opacity: .55; box-shadow: 0 0 0 .3rem rgba(16, 185, 129, 0); }
    }
    @media (max-width: 480px) {
        .mnf-bar { justify-content: center; text-align: center; }
        .mnf-spec-row { flex-direction: column; align-items: center; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mnf-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .mnf-status i { animation: none; box-shadow: none; }
    }
</style>

<div class="mnf-root" x-data="{
    dark: document.documentElement.dataset.theme === 'dark',
    toggle() {
        this.dark = !this.dark;
        document.documentElement.dataset.theme = this.dark ? 'dark' : 'light';
    },
}">
    <section class="pg-box" style="padding: 0; overflow: clip">
        <footer class="mnf-bar">
            <small>© {{ $num('2026') }} Meridian</small>
            <a class="mnf-status" href="#status">
                <i aria-hidden="true"></i>{{ $say('All systems operational', 'همهٔ سرویس‌ها سالم') }}
            </a>
            <nav class="mnf-links" aria-label="{{ $say('Footer', 'فوتر') }}">
                <a href="#docs">{{ $say('Docs', 'مستندات') }}</a>
                <a href="#api">{{ $say('API reference', 'مرجع API') }}</a>
                <a href="#privacy">{{ $say('Privacy', 'حریم خصوصی') }}</a>
            </nav>
            <button type="button" class="mnf-switch" role="switch"
                aria-label="{{ $say('Dark mode', 'تم تیره') }}"
                x-bind:aria-checked="dark ? 'true' : 'false'"
                x-on:click="toggle()"></button>
        </footer>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Even barer', 'باز هم لخت‌تر') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The same line with the switch dropped, and a start-aligned spread that carries status on one flank and links on the other.', 'همان سطر بدون سوییچ، و چیدمان راست‌چین که وضعیت را در یک سو و لینک‌ها را در سوی دیگر می‌نشاند.') }}
            </p>
        </div>
        <div class="mnf-spec">
            <div class="mnf-spec-row">
                <div class="mnf-spec-cell">
                    <footer class="mnf-bar">
                        <small>© {{ $num('2026') }} Meridian</small>
                        <a class="mnf-status" href="#status"><i aria-hidden="true"></i>{{ $say('All systems operational', 'همهٔ سرویس‌ها سالم') }}</a>
                        <nav class="mnf-links" aria-label="{{ $say('Footer', 'فوتر') }}">
                            <a href="#docs">{{ $say('Docs', 'مستندات') }}</a>
                            <a href="#privacy">{{ $say('Privacy', 'حریم خصوصی') }}</a>
                        </nav>
                    </footer>
                    <small>{{ $say('without the switch', 'بدون سوییچ') }}</small>
                </div>
                <div class="mnf-spec-cell">
                    <footer class="mnf-bar" data-variant="bare" style="inline-size: min(24rem, 86vw)">
                        <small>© {{ $num('2026') }} Meridian</small>
                        <nav class="mnf-links" aria-label="{{ $say('Footer', 'فوتر') }}">
                            <a href="#docs">{{ $say('Docs', 'مستندات') }}</a>
                            <a href="#api">{{ $say('API', 'API') }}</a>
                            <a href="#privacy">{{ $say('Privacy', 'حریم خصوصی') }}</a>
                        </nav>
                    </footer>
                    <small>{{ $say('start-aligned spread', 'گسترده و راست‌چین') }}</small>
                </div>
            </div>
        </div>
    </section>
</div>
