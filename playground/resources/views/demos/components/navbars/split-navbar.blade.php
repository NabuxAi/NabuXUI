{{--
    The center-logo navbar twice over: the desktop stage runs the 1fr auto 1fr
    grid — links flanking a mathematically centred wordmark over a hero — and
    the phone frame beside it carries the mobile story: the hamburger pulls a
    drawer in from the inline-start edge under a dark overlay, Escape and an
    outside click dismiss it, Tab stays trapped inside until then, and focus
    lands back on the hamburger when it closes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap');
    .spn-root {
        --spn-ink: #0f172a; --spn-muted: #64748b; --spn-surface: #ffffff; --spn-border: #e2e8f0;
        --spn-accent: #4f46e5; --spn-soft: #eef2ff; --spn-page: #f8fafc;
        --spn-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        font-family: var(--spn-font);
        color: var(--spn-ink);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .spn-root {
        --spn-ink: #e2e8f0; --spn-muted: #94a3b8; --spn-surface: #101a30; --spn-border: #24324f;
        --spn-accent: #818cf8; --spn-soft: #1c2748; --spn-page: #0b1120;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .spn-root {
            --spn-ink: #e2e8f0; --spn-muted: #94a3b8; --spn-surface: #101a30; --spn-border: #24324f;
            --spn-accent: #818cf8; --spn-soft: #1c2748; --spn-page: #0b1120;
        }
    }
    .spn-root, .spn-root *, .spn-root *::before, .spn-root *::after { box-sizing: border-box; }
    .spn-root :focus-visible { outline: 2px solid var(--spn-accent); outline-offset: 2px; }

    .spn-duo { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(22rem, 100%), 1fr));
               gap: 1.25rem; align-items: stretch; inline-size: min(100%, 56rem); margin-inline: auto; }
    .spn-duo-cell { display: grid; gap: .5rem; justify-items: center; }
    .spn-duo-cell > small { font-size: .72rem; color: var(--nx-text-muted); }

    /* ——— the shared navbar pieces ——— */
    .spn-nav { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: .75rem 1rem;
               padding: .9rem 1.25rem; }
    .spn-side { display: flex; align-items: center; gap: .125rem; }
    .spn-end { justify-self: end; gap: .375rem; }
    .spn-side a { padding: .45rem .75rem; border-radius: 999px; color: var(--spn-muted);
                  font: 500 .84rem/1 var(--spn-font); text-decoration: none; transition: color .15s ease, background-color .15s ease; }
    .spn-side a:hover { color: var(--spn-ink); background: var(--spn-soft); }
    .spn-brand { display: inline-flex; align-items: center; gap: .5rem; font-size: 1.02rem; font-weight: 700; white-space: nowrap; }
    .spn-brand i { inline-size: 1.35rem; block-size: 1.35rem; border-radius: .45rem;
                   background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
    .spn-cta { display: inline-flex; align-items: center; gap: .35rem; padding: .55rem 1.05rem; border-radius: 999px;
               background: var(--spn-accent); color: #fff; font: 600 .8rem/1 var(--spn-font); text-decoration: none;
               white-space: nowrap; }
    .spn-cta:hover { filter: brightness(1.08); }
    .spn-cta .nx-icon { inline-size: .9em; block-size: .9em; }

    /* ——— stage A: the desktop page ——— */
    .spn-stage { position: relative; min-block-size: 26.25rem; overflow: clip; border: 1px solid var(--spn-border);
                 border-radius: 1rem; background: var(--spn-page);
                 background-image: radial-gradient(50rem 16rem at 50% -4rem, color-mix(in srgb, var(--spn-accent) 12%, transparent), transparent 70%); }
    .spn-stage .spn-nav { border-block-end: 1px solid var(--spn-border);
                          background: color-mix(in srgb, var(--spn-surface) 82%, transparent); backdrop-filter: blur(8px); }
    .spn-hero { padding: 4.5rem 1.5rem 2.5rem; text-align: center; display: grid; gap: .75rem; justify-items: center; }
    .spn-hero h4 { margin: 0; font-size: clamp(1.25rem, 3.2vw, 1.75rem); font-weight: 800; letter-spacing: -.02em; }
    .spn-hero p { margin: 0; max-inline-size: 44ch; color: var(--spn-muted); font-size: .9rem; line-height: 1.7; }
    .spn-hero .spn-cta { margin-block-start: .4rem; }
    .spn-cities { display: flex; flex-wrap: wrap; justify-content: center; gap: .4rem; margin-block-start: 1rem; }
    .spn-cities span { padding: .35rem .7rem; border: 1px solid var(--spn-border); border-radius: 999px;
                       color: var(--spn-muted); font-size: .72rem; }

    /* ——— stage B: the phone frame with the drawer ——— */
    .spn-phone { position: relative; inline-size: min(100%, 22.5rem); min-block-size: 26.25rem; overflow: clip;
                 border: 1px solid var(--spn-border); border-radius: 1.75rem; background: var(--spn-page);
                 background-image: radial-gradient(30rem 12rem at 50% -3rem, color-mix(in srgb, var(--spn-accent) 14%, transparent), transparent 70%);
                 box-shadow: 0 18px 44px rgba(15, 23, 42, .14); }
    .spn-phone-bar { display: flex; align-items: center; gap: .6rem; padding: 1rem 1rem .8rem; }
    .spn-burger { display: inline-grid; place-items: center; inline-size: 2.4rem; block-size: 2.4rem; border: 1px solid var(--spn-border);
                  border-radius: .8rem; background: var(--spn-surface); color: var(--spn-ink); cursor: pointer;
                  transition: background-color .15s ease; }
    .spn-burger:hover { background: var(--spn-soft); }
    .spn-burger[aria-expanded='true'] { background: var(--spn-soft); border-color: color-mix(in srgb, var(--spn-accent) 40%, var(--spn-border)); }
    .spn-burger .nx-icon { inline-size: 1.1rem; block-size: 1.1rem; }
    .spn-phone-bar .spn-brand { margin-inline: auto; font-size: .95rem; }
    .spn-phone-bar .spn-dot-ava { inline-size: 2.1rem; block-size: 2.1rem; border-radius: 50%;
                                  background: conic-gradient(from 210deg, #22d3ee, #818cf8, #f472b6, #22d3ee);
                                  padding: 2px; }
    .spn-phone-bar .spn-dot-ava i { display: grid; place-items: center; inline-size: 100%; block-size: 100%;
                                     border-radius: 50%; background: var(--spn-surface); font-size: .66rem; font-weight: 700; font-style: normal; }
    .spn-phone-hero { padding: 2.75rem 1.25rem; text-align: center; display: grid; gap: .5rem; justify-items: center; }
    .spn-phone-hero b { font-size: 1.1rem; font-weight: 800; }
    .spn-phone-hero p { margin: 0; color: var(--spn-muted); font-size: .82rem; max-inline-size: 30ch; }

    .spn-overlay { position: absolute; inset: 0; z-index: 20; background: rgba(15, 23, 42, .55);
                   opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s; }
    .spn-overlay[data-open='true'] { opacity: 1; visibility: visible; transition: opacity .25s ease; }
    .spn-drawer { position: absolute; inset-block: 0; inset-inline-start: 0; z-index: 21; inline-size: min(78%, 16.5rem);
                  display: grid; gap: 1rem; align-content: start; padding: 1.15rem; overflow-y: auto;
                  background: var(--spn-surface); border-inline-end: 1px solid var(--spn-border);
                  box-shadow: 0 0 40px rgba(15, 23, 42, .25);
                  translate: -101% 0; visibility: hidden;
                  transition: translate .3s cubic-bezier(.2, 0, 0, 1), visibility 0s linear .3s; }
    html[dir="rtl"] .spn-drawer { translate: 101% 0; }
    .spn-drawer[data-open='true'] { translate: 0 0; visibility: visible;
                  transition: translate .3s cubic-bezier(.2, 0, 0, 1); }
    .spn-drawer-head { display: flex; align-items: center; gap: .5rem; }
    .spn-drawer-close { margin-inline-start: auto; display: inline-grid; place-items: center; inline-size: 2rem;
                        block-size: 2rem; border: 0; border-radius: .6rem; background: none; color: var(--spn-muted);
                        cursor: pointer; }
    .spn-drawer-close:hover { background: var(--spn-soft); color: var(--spn-ink); }
    .spn-drawer-close .nx-icon { inline-size: .95rem; block-size: .95rem; }
    .spn-drawer nav { display: grid; gap: .2rem; }
    .spn-drawer nav a { display: flex; align-items: center; gap: .55rem; padding: .6rem .65rem; border-radius: .65rem;
                        color: var(--spn-ink); font: 500 .88rem/1 var(--spn-font); text-decoration: none;
                        transition: background-color .14s ease; }
    .spn-drawer nav a:hover { background: var(--spn-soft); }
    .spn-drawer nav a .nx-icon { inline-size: 1rem; block-size: 1rem; color: var(--spn-muted); }
    .spn-drawer nav a:hover .nx-icon { color: var(--spn-accent); }
    .spn-drawer-note { margin: 0; padding: .75rem .8rem; border-radius: .7rem; background: var(--spn-soft);
                       color: var(--spn-muted); font-size: .74rem; line-height: 1.7; }
    :where(.nx-js) .pg:has(.spn-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 560px) {
        .spn-stage .spn-nav { grid-template-columns: 1fr; justify-items: center; gap: .5rem; }
        .spn-stage .spn-side { flex-wrap: wrap; justify-content: center; }
        .spn-end { justify-self: center; }
    }
    @media (prefers-reduced-motion: reduce) {
        .spn-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<div class="spn-root"
    x-data="{
        drawer: false,
        openDrawer() {
            this.drawer = true;
            this.$nextTick(() => { const f = this.$refs.drawer.querySelector('a[href], button'); f && f.focus(); });
        },
        closeDrawer() {
            if (! this.drawer) return;
            this.drawer = false;
            this.$refs.burger && this.$refs.burger.focus();
        },
        trap(e) {
            if (e.key !== 'Tab') return;
            const f = this.$refs.drawer.querySelectorAll('a[href], button:not([disabled])');
            if (! f.length) return;
            const first = f[0], last = f[f.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (! e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        },
    }"
    x-on:keydown.escape.window="closeDrawer()">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The logo holds the middle, on every screen', 'لوگو وسط را نگه می‌دارد، روی هر صفحه') }}</h3>
            <p style="margin: 0; max-width: 56ch; color: var(--nx-text-muted)">
                {{ $say('On the desktop stage the links flank a mathematically centred wordmark; in the phone frame the hamburger pulls the drawer in from the edge — overlay dims, Tab stays trapped inside, Escape and an outside click close it, and focus hops back to the burger.', 'در استیج دسکتاپ لینک‌ها دو طرف وردمارکِ دقیقاً مرکزی می‌ایستند؛ در قاب گوشی همبرگر دراور را از لبه می‌کشد — اورلی تیره می‌شود، تاب در دراور به دام می‌افتد، Escape و کلیک بیرون می‌بندد و فوکوس به همبرگر برمی‌گردد.') }}
            </p>
        </div>

        <div class="spn-duo">
            <div class="spn-duo-cell">
                <div class="spn-stage">
                    <header class="spn-nav">
                        <nav class="spn-side" aria-label="{{ $say('Primary, first half', 'اصلی، نیمهٔ اول') }}">
                            <a href="#product">{{ $say('Product', 'محصول') }}</a>
                            <a href="#solutions">{{ $say('Solutions', 'راه‌حل‌ها') }}</a>
                        </nav>
                        <b class="spn-brand"><i aria-hidden="true"></i>{{ $say('Meridian', 'مریدین') }}</b>
                        <span class="spn-side spn-end">
                            <a href="#pricing">{{ $say('Pricing', 'قیمت') }}</a>
                            <a href="#docs">{{ $say('Docs', 'مستندات') }}</a>
                            <a class="spn-cta" href="#start">{{ $say('Start free', 'شروع رایگان') }}<x-nx::icon name="arrow-right" /></a>
                        </span>
                    </header>
                    <div class="spn-hero">
                        <h4>{{ $say('Symmetry you can navigate by', 'تقارنی که می‌شود با آن ناوبری کرد') }}</h4>
                        <p>{{ $say('The 1fr auto 1fr grid keeps the wordmark centred no matter how long the flanks grow — try resizing.', 'شبکهٔ 1fr auto 1fr وردمارک را مرکز نگه می‌دارد، هر چقدر هم دو طرفش بلند شوند — عرض را تغییر دهید.') }}</p>
                        <a class="spn-cta" href="#start">{{ $say('Start free', 'شروع رایگان') }}<x-nx::icon name="arrow-right" /></a>
                        <div class="spn-cities" aria-hidden="true">
                            <span>{{ $say('Frankfurt', 'فرانکفورت') }}</span>
                            <span>{{ $say('Dublin', 'دوبلین') }}</span>
                            <span>{{ $say('Singapore', 'سنگاپور') }}</span>
                            <span>{{ $say('Toronto', 'تورنتو') }}</span>
                            <span>{{ $say('Osaka', 'اوساکا') }}</span>
                        </div>
                    </div>
                </div>
                <small>desktop · grid 1fr auto 1fr</small>
            </div>

            <div class="spn-duo-cell">
                <div class="spn-phone">
                    <div class="spn-phone-bar">
                        <button type="button" class="spn-burger" x-ref="burger"
                            x-bind:aria-expanded="drawer ? 'true' : 'false'" aria-controls="spn-drawer"
                            x-bind:aria-label="drawer ? '{{ $say('Close menu', 'بستن منو') }}' : '{{ $say('Open menu', 'بازکردن منو') }}'"
                            x-on:click="drawer ? closeDrawer() : openDrawer()">
                            <x-nx::icon name="menu" />
                        </button>
                        <b class="spn-brand"><i aria-hidden="true"></i>{{ $say('Meridian', 'مریدین') }}</b>
                        <span class="spn-dot-ava" aria-hidden="true"><i>LK</i></span>
                    </div>
                    <div class="spn-phone-hero">
                        <b>{{ $say('Release intelligence, pocket-sized', 'هوش انتشار، در جیب') }}</b>
                        <p>{{ $say('Tap the menu — the drawer slides in from the edge and keeps your Tab key until it closes.', 'منو را بزنید — دراور از لبه می‌آید و کلید Tab شما را تا بسته‌شدن نگه می‌دارد.') }}</p>
                    </div>

                    <div class="spn-overlay" x-bind:data-open="drawer ? 'true' : 'false'" x-on:click="closeDrawer()" aria-hidden="true"></div>
                    <aside class="spn-drawer" id="spn-drawer" x-ref="drawer"
                        x-bind:data-open="drawer ? 'true' : 'false'"
                        role="dialog" aria-modal="true" aria-label="{{ $say('Mobile menu', 'منوی موبایل') }}"
                        x-on:keydown="trap($event)">
                        <div class="spn-drawer-head">
                            <b class="spn-brand" style="font-size: .95rem"><i aria-hidden="true"></i>{{ $say('Meridian', 'مریدین') }}</b>
                            <button type="button" class="spn-drawer-close" x-on:click="closeDrawer()"
                                aria-label="{{ $say('Close menu', 'بستن منو') }}">
                                <x-nx::icon name="x" />
                            </button>
                        </div>
                        <nav aria-label="{{ $say('Mobile', 'موبایل') }}">
                            <a href="#product" x-on:click="closeDrawer()"><x-nx::icon name="grid" />{{ $say('Product', 'محصول') }}</a>
                            <a href="#solutions" x-on:click="closeDrawer()"><x-nx::icon name="layers" />{{ $say('Solutions', 'راه‌حل‌ها') }}</a>
                            <a href="#pricing" x-on:click="closeDrawer()"><x-nx::icon name="chart" />{{ $say('Pricing', 'قیمت') }}</a>
                            <a href="#docs" x-on:click="closeDrawer()"><x-nx::icon name="file" />{{ $say('Docs', 'مستندات') }}</a>
                            <a href="#changelog" x-on:click="closeDrawer()"><x-nx::icon name="trend-up" />{{ $say('Changelog', 'تغییرات') }}</a>
                        </nav>
                        <a class="spn-cta" href="#start" style="justify-self: start" x-on:click="closeDrawer()">{{ $say('Start free', 'شروع رایگان') }}<x-nx::icon name="arrow-right" /></a>
                        <p class="spn-drawer-note">{{ $say('SOC 2 Type II · GDPR · 12 regions worldwide', 'SOC 2 نوع ۲ · GDPR · ۱۲ ناحیه در سراسر جهان') }}</p>
                    </aside>
                </div>
                <small>{{ $say('mobile · drawer + overlay + focus trap', 'موبایل · دراور + اورلی + تلهٔ فوکوس') }}</small>
            </div>
        </div>
    </section>
</div>
