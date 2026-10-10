{{--
    The dock navbar at the bottom of a macOS-like console window: sweeping the
    pointer across the dock magnifies each tile on a gaussian curve (neighbours
    swell too), a tooltip rises above the hovered or focused item, the running
    item keeps its dot underneath, and clicking swaps destination — the window
    heading follows. A specimen row pins the magnification scale.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap');
    .dkn-root {
        --dkn-ink: #0f172a; --dkn-muted: #64748b; --dkn-surface: #ffffff; --dkn-border: #e2e8f0;
        --dkn-page: #f1f5f9; --dkn-accent: #4f46e5; --dkn-glass: rgba(255, 255, 255, .62);
        --dkn-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        font-family: var(--dkn-font);
        color: var(--dkn-ink);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .dkn-root {
        --dkn-ink: #e2e8f0; --dkn-muted: #94a3b8; --dkn-surface: #101a30; --dkn-border: #24324f;
        --dkn-page: #0b1120; --dkn-accent: #818cf8; --dkn-glass: rgba(16, 26, 48, .66);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .dkn-root {
            --dkn-ink: #e2e8f0; --dkn-muted: #94a3b8; --dkn-surface: #101a30; --dkn-border: #24324f;
            --dkn-page: #0b1120; --dkn-accent: #818cf8; --dkn-glass: rgba(16, 26, 48, .66);
        }
    }
    .dkn-root, .dkn-root *, .dkn-root *::before, .dkn-root *::after { box-sizing: border-box; }
    .dkn-root :focus-visible { outline: 2px solid var(--dkn-accent); outline-offset: 2px; }

    /* ——— the stage: a console window the dock floats over ——— */
    .dkn-stage { position: relative; inline-size: min(100%, 46rem); margin-inline: auto; min-block-size: 26.25rem;
                 display: flex; flex-direction: column; overflow: clip; border: 1px solid var(--dkn-border);
                 border-radius: 1rem; background: var(--dkn-page);
                 background-image: radial-gradient(50rem 16rem at 50% -4rem, color-mix(in srgb, var(--dkn-accent) 10%, transparent), transparent 70%); }
    .dkn-chrome { flex: none; display: flex; align-items: center; gap: .5rem; padding: .6rem .9rem;
                  border-block-end: 1px solid var(--dkn-border); background: var(--dkn-surface); }
    .dkn-lights { display: inline-flex; gap: .4rem; }
    .dkn-lights i { inline-size: .75rem; block-size: .75rem; border-radius: 50%; }
    .dkn-lights i:nth-child(1) { background: #f87171; }
    .dkn-lights i:nth-child(2) { background: #fbbf24; }
    .dkn-lights i:nth-child(3) { background: #34d399; }
    .dkn-chrome b { font-size: .8rem; font-weight: 600; color: var(--dkn-muted); }
    .dkn-body { flex: 1; padding: 1.5rem 1.25rem 6.5rem; display: grid; gap: 1rem; align-content: start; }
    .dkn-body h4 { margin: 0; font-size: 1.15rem; font-weight: 700; }
    .dkn-body > p { margin: 0; color: var(--dkn-muted); font-size: .85rem; }
    .dkn-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(10rem, 100%), 1fr)); gap: .7rem; }
    .dkn-card { display: grid; gap: .3rem; padding: .9rem 1rem; border: 1px solid var(--dkn-border); border-radius: .8rem;
                background: var(--dkn-surface); }
    .dkn-card small { color: var(--dkn-muted); font-size: .72rem; }
    .dkn-card b { font-size: 1.15rem; font-weight: 700; }
    .dkn-list { display: grid; gap: .45rem; }
    .dkn-row { display: flex; align-items: center; gap: .6rem; padding: .55rem .75rem; border: 1px solid var(--dkn-border);
               border-radius: .7rem; background: var(--dkn-surface); font-size: .82rem; }
    .dkn-row .nx-icon { inline-size: 1rem; block-size: 1rem; color: var(--dkn-accent); }
    .dkn-row small { margin-inline-start: auto; color: var(--dkn-muted); }

    /* ——— the dock ——— */
    .dkn-dock { position: absolute; inset-block-end: .9rem; inset-inline: 0; z-index: 6; display: flex;
                justify-content: center; }
    .dkn-bar { display: flex; align-items: flex-end; gap: .55rem; padding: .55rem .8rem; border-radius: 1.35rem;
               border: 1px solid color-mix(in srgb, var(--dkn-ink) 10%, transparent);
               background: var(--dkn-glass); backdrop-filter: blur(16px) saturate(160%);
               -webkit-backdrop-filter: blur(16px) saturate(160%);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3), 0 14px 34px rgba(15, 23, 42, .22); }
    .dkn-item { position: relative; display: grid; justify-items: center; border: 0; background: none; padding: 0 0 .45rem;
                cursor: pointer; color: var(--dkn-ink); }
    .dkn-box { display: grid; place-items: center; inline-size: calc(2.55rem * var(--dkn-s, 1));
               block-size: calc(2.55rem * var(--dkn-s, 1)); border-radius: calc(.68rem * var(--dkn-s, 1));
               background: linear-gradient(160deg, #6366f1, #8b5cf6); color: #fff;
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .35), 0 3px 8px rgba(15, 23, 42, .25);
               transition: inline-size .13s ease-out, block-size .13s ease-out, border-radius .13s ease-out; }
    .dkn-box .nx-icon { inline-size: calc(1.15rem * var(--dkn-s, 1)); block-size: calc(1.15rem * var(--dkn-s, 1)); }
    .dkn-item[data-active='true'] .dkn-box { background: linear-gradient(160deg, #0ea5e9, #6366f1); }
    .dkn-dot { inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor; opacity: 0;
               transition: opacity .2s ease; }
    .dkn-item[data-active='true'] .dkn-dot { opacity: .85; }
    .dkn-badge { position: absolute; inset-block-start: calc(-.15rem * var(--dkn-s, 1)); inset-inline-end: calc(-.15rem * var(--dkn-s, 1));
                 min-inline-size: 1rem; padding-inline: .25rem; block-size: 1rem; display: grid; place-items: center;
                 border-radius: .6rem; background: #ef4444; color: #fff; font: 700 .62rem/1 var(--dkn-font);
                 box-shadow: 0 0 0 2px var(--dkn-glass); }
    .dkn-item::before { content: attr(data-tip); position: absolute; inset-block-end: calc(100% + .35rem);
                        left: 50%; translate: -50% .25rem; padding: .32rem .6rem; border-radius: .5rem;
                        background: var(--dkn-ink); color: var(--dkn-page); font: 600 .7rem/1 var(--dkn-font);
                        white-space: nowrap; pointer-events: none; opacity: 0; visibility: hidden;
                        transition: opacity .16s ease, translate .16s ease, visibility .16s; }
    .dkn-item:hover::before, .dkn-item:focus-visible::before { opacity: 1; visibility: visible; translate: -50% 0; }

    /* ——— specimen: the magnification scale pinned ——— */
    .dkn-spec { display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; align-items: flex-end; }
    .dkn-spec-cell { display: grid; gap: .55rem; justify-items: center; }
    .dkn-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .dkn-spec .dkn-bar { position: static; }
    :where(.nx-js) .pg:has(.dkn-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .dkn-bar { gap: .4rem; padding-inline: .6rem; }
        .dkn-body { padding-block-end: 6rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .dkn-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<div class="dkn-root"
    x-data="{
        active: 'overview',
        views: {
            overview: '{{ $say('Overview', 'نمای کلی') }}',
            analytics: '{{ $say('Analytics', 'تحلیل') }}',
            team: '{{ $say('Team', 'تیم') }}',
            inbox: '{{ $say('Inbox', 'صندوق ورودی') }}',
            files: '{{ $say('Files', 'فایل‌ها') }}',
            settings: '{{ $say('Settings', 'تنظیمات') }}',
        },
        move(e) {
            this.$refs.dock.querySelectorAll('.dkn-item').forEach(el => {
                const r = el.getBoundingClientRect();
                const d = Math.abs(e.clientX - (r.left + r.width / 2));
                const s = 1 + .55 * Math.exp(-.5 * (d / 64) ** 2);
                el.style.setProperty('--dkn-s', s.toFixed(3));
            });
        },
        reset() {
            this.$refs.dock.querySelectorAll('.dkn-item').forEach(el => el.style.setProperty('--dkn-s', 1));
        },
    }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sweep the pointer across the dock', 'موس را روی داک بکشید') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Tiles magnify on a gaussian curve — the one under the cursor peaking at 1.55×, neighbours swelling politely — the tooltip rises above hover and focus, and the dot parks under the running app. Click to switch; the heading follows.', 'کاشی‌ها با منحنی گاوسی بزرگ می‌شوند — آنکه زیر موس است به ۱٫۵۵ برابر می‌رسد و همسایه‌ها مؤدبانه باد می‌کنند — تولتیپ بالای هاور و فوکوس می‌آید و نقطه زیر برنامهٔ فعال می‌نشیند. کلیک کنید تا عوض شود؛ تیترِ پنجره هم همراهش می‌رود.') }}
            </p>
        </div>

        <div class="dkn-stage">
            <div class="dkn-chrome" aria-hidden="true">
                <span class="dkn-lights"><i></i><i></i><i></i></span>
                <b>{{ $say('Meridian — Console', 'مریدین — کنسول') }}</b>
            </div>

            <div class="dkn-body">
                <h4 x-text="views[active]"></h4>
                <p>{{ $say('Follow-the-sun operations across every region you ship to.', 'عملیات خورشیدگرد روی هر ناحیه‌ای که منتشر می‌کنید.') }}</p>
                <div class="dkn-cards">
                    <div class="dkn-card"><small>{{ $say('Deploys this week', 'استقرار این هفته') }}</small><b>{{ $num('218') }}</b></div>
                    <div class="dkn-card"><small>{{ $say('p95 latency', 'تأخیر p95') }}</small><b>{{ $num('214') }}ms</b></div>
                    <div class="dkn-card"><small>{{ $say('Error budget left', 'بودجهٔ خطا') }}</small><b>{{ $num('87') }}%</b></div>
                </div>
                <div class="dkn-list">
                    <div class="dkn-row"><x-nx::icon name="zap" />{{ $say('Rollout eu-de-2 finished', 'استقرار eu-de-2 تمام شد') }}<small>{{ $say('4 min ago', '۴ دقیقه پیش') }}</small></div>
                    <div class="dkn-row"><x-nx::icon name="bell" />{{ $say('Latency spike in ap-se-1 resolved', 'جهش تأخیر در ap-se-1 حل شد') }}<small>{{ $say('22 min ago', '۲۲ دقیقه پیش') }}</small></div>
                    <div class="dkn-row"><x-nx::icon name="users" />{{ $say('Priya rotated on-call for Toronto', 'پریا آن‌کال تورنتو را تحویل گرفت') }}<small>{{ $say('1 h ago', '۱ ساعت پیش') }}</small></div>
                </div>
            </div>

            <div class="dkn-dock">
                <nav class="dkn-bar" x-ref="dock" aria-label="{{ $say('App navigation', 'ناوبری برنامه') }}"
                    x-on:pointermove="move($event)" x-on:pointerleave="reset()">
                    <button type="button" class="dkn-item" data-tip="{{ $say('Overview', 'نمای کلی') }}"
                        x-bind:data-active="active === 'overview' ? 'true' : 'false'" x-bind:aria-pressed="active === 'overview'"
                        x-on:click="active = 'overview'">
                        <span class="dkn-box"><x-nx::icon name="home" /></span><i class="dkn-dot" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="dkn-item" data-tip="{{ $say('Analytics', 'تحلیل') }}"
                        x-bind:data-active="active === 'analytics' ? 'true' : 'false'" x-bind:aria-pressed="active === 'analytics'"
                        x-on:click="active = 'analytics'">
                        <span class="dkn-box"><x-nx::icon name="chart" /></span><i class="dkn-dot" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="dkn-item" data-tip="{{ $say('Team', 'تیم') }}"
                        x-bind:data-active="active === 'team' ? 'true' : 'false'" x-bind:aria-pressed="active === 'team'"
                        x-on:click="active = 'team'">
                        <span class="dkn-box"><x-nx::icon name="users" /></span><i class="dkn-dot" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="dkn-item" x-bind:data-tip="'{{ $say('Inbox', 'صندوق ورودی') }} · ' + '{{ $num('3') }}' + ' {{ $say('unread', 'ناخوانده') }}'"
                        x-bind:data-active="active === 'inbox' ? 'true' : 'false'" x-bind:aria-pressed="active === 'inbox'"
                        x-on:click="active = 'inbox'">
                        <span class="dkn-box"><x-nx::icon name="message" /><i class="dkn-badge">{{ $num('3') }}</i></span><i class="dkn-dot" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="dkn-item" data-tip="{{ $say('Files', 'فایل‌ها') }}"
                        x-bind:data-active="active === 'files' ? 'true' : 'false'" x-bind:aria-pressed="active === 'files'"
                        x-on:click="active = 'files'">
                        <span class="dkn-box"><x-nx::icon name="folder" /></span><i class="dkn-dot" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="dkn-item" data-tip="{{ $say('Settings', 'تنظیمات') }}"
                        x-bind:data-active="active === 'settings' ? 'true' : 'false'" x-bind:aria-pressed="active === 'settings'"
                        x-on:click="active = 'settings'">
                        <span class="dkn-box"><x-nx::icon name="settings" /></span><i class="dkn-dot" aria-hidden="true"></i>
                    </button>
                </nav>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The scale, pinned', 'مقیاس، پین‌شده') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Idle, the polite neighbour at 1.3× and the tile under the cursor at 1.55× — the running app keeps its sky gradient and dot.', 'بیکار، همسایهٔ مؤدب در ۱٫۳ برابر و کاشی زیر موس در ۱٫۵۵ برابر — برنامهٔ فعال گرادیان آسمانی و نقطه‌اش را نگه می‌دارد.') }}
            </p>
        </div>
        <div class="dkn-spec">
            <div class="dkn-spec-cell">
                <span class="dkn-bar" aria-hidden="true">
                    <span class="dkn-item" style="cursor: default"><span class="dkn-box"><x-nx::icon name="home" /></span><i class="dkn-dot"></i></span>
                </span>
                <small>var(--dkn-s) = {{ $num('1') }}</small>
            </div>
            <div class="dkn-spec-cell">
                <span class="dkn-bar" aria-hidden="true">
                    <span class="dkn-item" style="--dkn-s: 1.3; cursor: default"><span class="dkn-box"><x-nx::icon name="folder" /></span><i class="dkn-dot"></i></span>
                </span>
                <small>var(--dkn-s) = {{ $num('1.3') }}</small>
            </div>
            <div class="dkn-spec-cell">
                <span class="dkn-bar" aria-hidden="true">
                    <span class="dkn-item" style="--dkn-s: 1.55; cursor: default" data-active="true"><span class="dkn-box"><x-nx::icon name="message" /><i class="dkn-badge">{{ $num('3') }}</i></span><i class="dkn-dot"></i></span>
                </span>
                <small>var(--dkn-s) = {{ $num('1.55') }}</small>
            </div>
        </div>
    </section>
</div>
