{{--
    micro-controls' real scenario: the preferences panel of Vertex Studio —
    icon-animated controls riding native inputs so the keyboard works for
    free: a hamburger that rotates into an X while its menu unfurls, a
    checkbox whose tick draws and pops, a sun/moon switch whose rays fold
    away, a password eye that blinks, and accordions with a spinning
    chevron.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .imm-panel {
        --imm-fg: #1c1917; --imm-muted: #78716c; --imm-border: #e7e5e4;
        --imm-bg: #ffffff; --imm-accent: #4f46e5; --imm-soft: #eef2ff;
        display: grid;
        gap: 0;
        max-inline-size: 46rem;
        border: 1px solid var(--imm-border);
        border-radius: 1.1rem;
        background: var(--imm-bg);
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--imm-fg);
        overflow: clip;
    }
    html[data-theme="dark"] .imm-panel {
        --imm-fg: #fafaf9; --imm-muted: #a8a29e; --imm-border: #292524;
        --imm-bg: #1c1917; --imm-accent: #a5b4fc; --imm-soft: #26264a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .imm-panel {
            --imm-fg: #fafaf9; --imm-muted: #a8a29e; --imm-border: #292524;
            --imm-bg: #1c1917; --imm-accent: #a5b4fc; --imm-soft: #26264a;
        }
    }
    .imm-row {
        display: grid;
        gap: .75rem;
        padding: 1.05rem 1.25rem;
        border-block-start: 1px solid var(--imm-border);
    }
    .imm-row:first-child { border-block-start: none; }
    .imm-rowhead { display: flex; align-items: baseline; gap: .7rem; flex-wrap: wrap; }
    .imm-title { margin: 0; font-size: .86rem; font-weight: 700; }
    .imm-kbd {
        font-family: ui-monospace, monospace; font-size: .62rem; color: var(--imm-muted);
        border: 1px solid var(--imm-border); border-radius: .35rem; padding: .1rem .35rem;
    }
    .imm-note { margin: 0; font-size: .74rem; line-height: 1.6; color: var(--imm-muted); }
    .imm-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* hamburger ⇄ X — three spans rotate into a cross */
    .imm-burger {
        display: inline-grid; gap: 6px; align-content: center;
        inline-size: 2.6rem; block-size: 2.6rem; padding: 0;
        border: 1px solid var(--imm-border); border-radius: .7rem;
        background: var(--imm-bg); color: var(--imm-fg); cursor: pointer;
    }
    .imm-burger span {
        display: block; inline-size: 1.15rem; block-size: 2px;
        margin-inline: auto; border-radius: 2px; background: currentColor;
        transition: translate .3s cubic-bezier(.4, 0, .2, 1), rotate .3s cubic-bezier(.4, 0, .2, 1), opacity .2s ease, scale .3s ease;
    }
    .imm-burger[aria-expanded="true"] span:nth-child(1) { translate: 0 8px; rotate: 45deg; }
    .imm-burger[aria-expanded="true"] span:nth-child(2) { opacity: 0; scale: .2 1; }
    .imm-burger[aria-expanded="true"] span:nth-child(3) { translate: 0 -8px; rotate: -45deg; }
    .imm-menu { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s cubic-bezier(.4, 0, .2, 1); }
    .imm-menu[data-open="true"] { grid-template-rows: 1fr; }
    .imm-menuin { overflow: hidden; }
    .imm-menu ul { display: grid; gap: .1rem; margin: .35rem 0 0; padding: 0; list-style: none; }
    .imm-menu a {
        display: block; padding: .45rem .7rem; border-radius: .55rem;
        font-size: .8rem; font-weight: 500; color: var(--imm-fg); text-decoration: none;
    }
    .imm-menu a:hover { background: var(--imm-soft); color: var(--imm-accent); }

    /* checkbox — the tick draws itself, then the box pops */
    .imm-check { display: inline-flex; align-items: center; gap: .7rem; cursor: pointer; }
    .imm-check input { position: absolute; inline-size: 1px; block-size: 1px; margin: -1px; opacity: 0; }
    .imm-box {
        flex: none; display: grid; place-items: center;
        inline-size: 1.35rem; block-size: 1.35rem;
        border: 2px solid var(--imm-border); border-radius: .45rem;
        color: #fff; background: var(--imm-bg);
        transition: background-color .2s ease, border-color .2s ease;
    }
    .imm-box svg { inline-size: .85rem; block-size: .85rem; }
    .imm-box path {
        fill: none; stroke: currentColor; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
        stroke-dasharray: 1; stroke-dashoffset: 1;
        transition: stroke-dashoffset .3s ease .05s;
    }
    .imm-check input:checked + .imm-box { background: var(--imm-accent); border-color: var(--imm-accent); animation: imm-pop .3s cubic-bezier(.2, .9, .3, 1.4); }
    .imm-check input:checked + .imm-box path { stroke-dashoffset: 0; }
    .imm-check input:focus-visible + .imm-box { outline: 2px solid var(--imm-accent); outline-offset: 2px; }
    .imm-check span { font-size: .8rem; font-weight: 500; }

    /* sun ⇄ moon switch — the sun rotates away, its rays fold, the moon rises */
    .imm-switch { display: inline-flex; align-items: center; gap: .7rem; cursor: pointer; }
    .imm-switch input { position: absolute; inline-size: 1px; block-size: 1px; margin: -1px; opacity: 0; }
    .imm-track {
        position: relative; display: block; inline-size: 3.4rem; block-size: 1.9rem;
        border-radius: 999px; background: var(--imm-border);
        transition: background-color .35s ease;
    }
    .imm-knob {
        position: absolute; inset-block-start: .25rem; inset-inline-start: .25rem;
        display: grid; place-items: center;
        inline-size: 1.4rem; block-size: 1.4rem; border-radius: 50%;
        background: #fff; color: #f59e0b;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .25);
        transition: inset-inline-start .35s cubic-bezier(.4, 0, .2, 1);
    }
    .imm-knob svg { position: absolute; inline-size: .9rem; block-size: .9rem; transition: rotate .45s ease, opacity .3s ease, scale .3s ease; }
    .imm-sun { transform-box: fill-box; transform-origin: center; }
    .imm-rays line { transition: scale .3s ease; transform-box: fill-box; transform-origin: center; }
    .imm-switch input:checked ~ .imm-track { background: #312e81; }
    .imm-switch input:checked ~ .imm-track .imm-knob { inset-inline-start: calc(100% - 1.65rem); }
    .imm-switch input:checked ~ .imm-track .imm-sun { rotate: -100deg; opacity: 0; scale: .4; }
    .imm-switch input:checked ~ .imm-track .imm-rays line { scale: 0; }
    .imm-switch input:checked ~ .imm-track .imm-moon { rotate: 0deg; opacity: 1; scale: 1; }
    .imm-moon { opacity: 0; scale: .4; rotate: 90deg; color: #6366f1; }
    .imm-switch input:focus-visible ~ .imm-track { outline: 2px solid var(--imm-accent); outline-offset: 2px; }
    .imm-switch span { font-size: .8rem; font-weight: 500; }

    /* password eye — the lid drops over the eye and the slash crosses it */
    .imm-pass { display: flex; align-items: center; gap: .5rem; max-inline-size: 20rem;
                padding: .5rem .5rem .5rem .9rem; border: 1px solid var(--imm-border); border-radius: .75rem; }
    .imm-pass:focus-within { border-color: var(--imm-accent); }
    .imm-pass input { flex: 1; min-inline-size: 0; border: none; outline: none; background: transparent;
                      font: 500 .8rem/1.4 ui-monospace, monospace; color: var(--imm-fg); }
    .imm-eye {
        flex: none; display: grid; place-items: center;
        inline-size: 2rem; block-size: 2rem; padding: 0;
        border: none; border-radius: .55rem; background: transparent;
        color: var(--imm-muted); cursor: pointer; transition: color .2s ease, background-color .2s ease;
    }
    .imm-eye:hover { color: var(--imm-accent); background: var(--imm-soft); }
    .imm-eye:focus-visible { outline: 2px solid var(--imm-accent); outline-offset: 1px; }
    .imm-eye svg { inline-size: 1.15rem; block-size: 1.15rem; overflow: visible; }
    .imm-lid { transform-box: fill-box; transform-origin: 50% 0%; scale: 1 0; transition: scale .22s ease; }
    .imm-eye[data-shown="false"] .imm-lid { scale: 1 1; }
    .imm-slash { stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .22s ease .06s; }
    .imm-eye[data-shown="false"] .imm-slash { stroke-dashoffset: 0; }

    /* accordion — native details, the chevron spins open */
    .imm-acc { border: 1px solid var(--imm-border); border-radius: .75rem; overflow: clip; }
    .imm-acc + .imm-acc { margin-block-start: .5rem; }
    .imm-acc summary {
        display: flex; align-items: center; gap: .7rem; padding: .8rem 1rem;
        font-size: .8rem; font-weight: 600; cursor: pointer; list-style: none;
    }
    .imm-acc summary::-webkit-details-marker { display: none; }
    .imm-acc summary svg { margin-inline-start: auto; inline-size: 1rem; block-size: 1rem;
                           transition: rotate .3s cubic-bezier(.4, 0, .2, 1); }
    .imm-acc[open] summary svg { rotate: 180deg; }
    .imm-acc[open] summary { color: var(--imm-accent); }
    .imm-accbody { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .3s cubic-bezier(.4, 0, .2, 1); }
    .imm-acc[open] .imm-accbody { grid-template-rows: 1fr; }
    .imm-accin { overflow: hidden; }
    .imm-acc p { margin: 0; padding: 0 1rem .85rem; font-size: .76rem; line-height: 1.7; color: var(--imm-muted); }

    @keyframes imm-pop { 0% { scale: .8; } 55% { scale: 1.12; } 100% { scale: 1; } }
    @media (prefers-reduced-motion: reduce) {
        .imm-burger span, .imm-menu, .imm-box, .imm-box path, .imm-track, .imm-knob,
        .imm-knob svg, .imm-rays line, .imm-lid, .imm-slash, .imm-acc summary svg, .imm-accbody {
            transition-duration: .01ms;
        }
        .imm-check input:checked + .imm-box { animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Small icons, serious controls', 'آیکون‌های کوچک، کنترل‌های جدی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Every animated icon here rides a native control, so the keyboard keeps working for free: the hamburger rotates into an X while its menu unfurls, the checkbox tick draws itself then pops, the sun spins away as its rays fold into a moon, the password eye blinks its lid, and the accordion chevron spins — Space, Enter and Tab do the rest.', 'هر آیکون متحرک اینجا روی یک کنترل بومی سوار است، پس کیبورد مجانی کار می‌کند: همبرگر با چرخش خطوطش ضربدر می‌شود و منوش باز می‌شود، تیک چک‌باکس خودش را می‌کشد و پاپ می‌کند، خورشید با جمع‌شدن پرتوها چرخیده و جایش را به ماه می‌دهد، چشم رمز پلک می‌زند و شوران آکاردئون می‌چرخد — Space و Enter و Tab بقیه‌اش را انجام می‌دهند.') }}
        </p>
    </div>

    <div class="imm-panel" x-data="{ menu: false, shown: false, fa: {{ $fa ? 'true' : 'false' }} }">
        {{-- hamburger ⇄ X --}}
        <div class="imm-row">
            <div class="imm-rowhead">
                <h4 class="imm-title">{{ $say('Project menu', 'منوی پروژه') }}</h4>
                <span class="imm-kbd">{{ $say('Enter · Space', 'Enter · Space') }}</span>
            </div>
            <button type="button" class="imm-burger" x-bind:aria-expanded="menu ? 'true' : 'false'"
                    aria-controls="imm-menu" x-on:click="menu = !menu">
                <span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span>
                <span class="sr-only" x-text="menu ? (fa ? 'بستن منو' : 'Close menu') : (fa ? 'باز کردن منو' : 'Open menu')" style="position: absolute; inline-size: 1px; block-size: 1px; overflow: hidden; clip-path: inset(50%)"></span>
            </button>
            <div class="imm-menu" id="imm-menu" x-bind:data-open="menu ? 'true' : 'false'">
                <div class="imm-menuin">
                    <ul>
                        <li><a href="#dashboard">{{ $say('Dashboard', 'داشبورد') }}</a></li>
                        <li><a href="#files">{{ $say('Files', 'فایل‌ها') }}</a></li>
                        <li><a href="#settings">{{ $say('Settings', 'تنظیمات') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- checkbox with drawing tick --}}
        <div class="imm-row">
            <div class="imm-rowhead">
                <h4 class="imm-title">{{ $say('Release consent', 'موافقت انتشار') }}</h4>
                <span class="imm-kbd">{{ $say('Space', 'Space') }}</span>
            </div>
            <label class="imm-check">
                <input type="checkbox">
                <span class="imm-box">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path pathLength="1" d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                </span>
                <span>{{ $say('I confirm the build passed all checks', 'تأیید می‌کنم که بیلد همهٔ بررسی‌ها را گذرانده') }}</span>
            </label>
        </div>

        {{-- sun ⇄ moon switch --}}
        <div class="imm-row">
            <div class="imm-rowhead">
                <h4 class="imm-title">{{ $say('Night mode', 'حالت شب') }}</h4>
                <span class="imm-kbd">{{ $say('Space', 'Space') }}</span>
            </div>
            <label class="imm-switch">
                <input type="checkbox" role="switch">
                <span class="imm-track">
                    <span class="imm-knob">
                        <svg class="imm-sun" viewBox="0 0 24 24" aria-hidden="true">
                            <g class="imm-rays">
                                <line x1="12" y1="2.5" x2="12" y2="4.5"/><line x1="12" y1="19.5" x2="12" y2="21.5"/>
                                <line x1="2.5" y1="12" x2="4.5" y2="12"/><line x1="19.5" y1="12" x2="21.5" y2="12"/>
                                <line x1="5.3" y1="5.3" x2="6.7" y2="6.7"/><line x1="17.3" y1="17.3" x2="18.7" y2="18.7"/>
                                <line x1="5.3" y1="18.7" x2="6.7" y2="17.3"/><line x1="17.3" y1="6.7" x2="18.7" y2="5.3"/>
                            </g>
                            <circle cx="12" cy="12" r="4" fill="currentColor" stroke="none"/>
                        </svg>
                        <svg class="imm-moon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" stroke="none" d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                        </svg>
                    </span>
                </span>
                <span>{{ $say('Dim the studio after sunset', 'استودیو را بعد از غروب کم‌نور کن') }}</span>
            </label>
        </div>

        {{-- password eye --}}
        <div class="imm-row">
            <div class="imm-rowhead">
                <h4 class="imm-title">{{ $say('Account password', 'رمز حساب') }}</h4>
                <span class="imm-kbd">{{ $say('Tab · Enter', 'Tab · Enter') }}</span>
            </div>
            <div class="imm-pass">
                <input :type="shown ? 'text' : 'password'"
                       :placeholder="fa ? 'حداقل ۸ کاراکتر' : 'At least 8 characters'"
                       :aria-label="fa ? 'رمز حساب' : 'Account password'"
                       autocomplete="new-password">
                <button type="button" class="imm-eye" x-bind:data-shown="shown ? 'true' : 'false'"
                        x-on:click="shown = !shown"
                        :aria-label="shown ? (fa ? 'پنهان کردن رمز' : 'Hide password') : (fa ? 'نمایش رمز' : 'Show password')"
                        :aria-pressed="shown ? 'true' : 'false'">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="imm-line" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/>
                        <circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/>
                        <path class="imm-line imm-lid" d="M4.5 7.5C3 9 2.5 10.5 2.5 12"/>
                        <path class="imm-line imm-slash" pathLength="1" d="M4 4l16 16"/>
                    </svg>
                </button>
            </div>
            <p class="imm-note">{{ $say('The lid drops over the eye and the slash draws itself while the field stays a real password input.', 'پلک روی چشم می‌افتد و خطِ خطر خودش را می‌کشد، در حالی که فیلد همچنان یک اینپوت رمز واقعی است.') }}</p>
        </div>

        {{-- accordion with spinning chevron --}}
        <div class="imm-row">
            <div class="imm-rowhead">
                <h4 class="imm-title">{{ $say('Deployment FAQ', 'پرسش‌های پرتکرار استقرار') }}</h4>
                <span class="imm-kbd">{{ $say('Enter', 'Enter') }}</span>
            </div>
            <details class="imm-acc">
                <summary>
                    {{ $say('Where do builds land?', 'بیلدها کجا می‌نشینند؟') }}
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path class="imm-line" d="m6 9 6 6 6-6"/></svg>
                </summary>
                <div class="imm-accbody"><div class="imm-accin">
                    <p>{{ $say('Every tag lands on the Dublin edge first, then replicates to Frankfurt and Singapore within a minute.', 'هر تگ اول روی لبهٔ دوبلین می‌نشیند و بعد در کمتر از یک دقیقه به فرانکفورت و سنگاپور تکثیر می‌شود.') }}</p>
                </div></div>
            </details>
            <details class="imm-acc">
                <summary>
                    {{ $say('Can I pause a rollout?', 'می‌توانم انتشار را نگه دارم؟') }}
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path class="imm-line" d="m6 9 6 6 6-6"/></svg>
                </summary>
                <div class="imm-accbody"><div class="imm-accin">
                    <p>{{ $say('Yes — hold the release from the console; the flag freezes in place and resumes exactly where it stopped.', 'بله — انتشار را از کنسول نگه دار؛ فلگ همان‌جا می‌ایستد و از همان نقطه ادامه می‌دهد.') }}</p>
                </div></div>
            </details>
        </div>
    </div>
</section>
