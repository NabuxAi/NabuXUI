{{--
    iOS 26's floating liquid-glass tab bar on a small music app: the selection
    droplet rolls between the four tabs on a spring, the glass has a lit rim
    and refracted light, tapping the current tab scrolls the stage to top, and
    a clear-variant toggle shows the fully translucent recipe.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .iotab-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        --iotab-glass: rgba(255, 255, 255, .78); --iotab-glass-clear: rgba(255, 255, 255, .14);
        --iotab-tint-soft: rgba(0, 122, 255, .14);
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .iotab-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        --iotab-glass: rgba(30, 30, 30, .62); --iotab-glass-clear: rgba(255, 255, 255, .07);
        --iotab-tint-soft: rgba(10, 132, 255, .24);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .iotab-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
            --iotab-glass: rgba(30, 30, 30, .62); --iotab-glass-clear: rgba(255, 255, 255, .07);
            --iotab-tint-soft: rgba(10, 132, 255, .24);
        }
    }
    .iotab-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .iotab-phone { position: relative; inline-size: min(100%, 22rem); margin-inline: auto; padding: .55rem; border-radius: 2.9rem;
                   background: #101014; box-shadow: 0 24px 60px rgba(0, 0, 0, .28), inset 0 0 0 1px rgba(255, 255, 255, .12); }
    .iotab-screen { position: relative; overflow: clip; display: flex; flex-direction: column; block-size: 38rem; border-radius: 2.4rem; background: var(--ios-bg); }
    .iotab-pill { position: absolute; inset-block-start: .55rem; left: 50%; translate: -50% 0; inline-size: 5.2rem; block-size: 1.45rem;
                  border-radius: 999px; background: #101014; z-index: 3; }
    .iotab-status { display: flex; align-items: center; justify-content: space-between; padding: .75rem 1.6rem .3rem; font: 600 .95rem/-apple-system, system-ui, sans-serif; }
    .iotab-status-end { display: flex; align-items: center; gap: .35rem; }
    .iotab-status-end .bars { display: flex; align-items: flex-end; gap: 1.5px; }
    .iotab-status-end .bars i { inline-size: 3px; border-radius: 1px; background: var(--ios-label); }
    .iotab-status-end .bars i:nth-child(1) { block-size: 4px; } .iotab-status-end .bars i:nth-child(2) { block-size: 6px; }
    .iotab-status-end .bars i:nth-child(3) { block-size: 8px; } .iotab-status-end .bars i:nth-child(4) { block-size: 10px; opacity: .35; }
    .iotab-batt { position: relative; inline-size: 23px; block-size: 11px; border: 1px solid var(--ios-label-3); border-radius: 3.5px; }
    .iotab-batt::before { content: ''; position: absolute; inset: 1.5px; inset-inline-end: 5px; border-radius: 2px; background: var(--ios-label); }
    .iotab-batt::after { content: ''; position: absolute; inset-block: 3px; inset-inline-end: -3px; inline-size: 2px; border-radius: 0 2px 2px 0; background: var(--ios-label-3); }

    .iotab-body { flex: 1; overflow-y: auto; scrollbar-width: none; padding: .6rem .9rem 7.5rem;
                  /* Rows at rest can land on the bottom strip and get sliced mid-glyph
                     by the dark home indicator at the screen edge; keep that strip
                     clear (flat zero) and dissolve content back in just above it,
                     completing the fade at the glass bar's bottom edge so rows stay
                     fully opaque behind the bar's blur. */
                  -webkit-mask-image: linear-gradient(to top, transparent 0, transparent .8rem, #000 1.5rem);
                  mask-image: linear-gradient(to top, transparent 0, transparent .8rem, #000 1.5rem); }
    .iotab-body::-webkit-scrollbar { display: none; }
    .iotab-hello { font: 700 1.55rem/-apple-system, system-ui, sans-serif; margin: .4rem 0 .9rem; }
    .iotab-hero { position: relative; display: flex; align-items: center; gap: .8rem; padding: .9rem; border-radius: 14px; overflow: clip;
                  background: linear-gradient(120deg, #5856D6, #0A2A6B 75%); color: #fff; box-shadow: 0 10px 24px rgba(30, 30, 90, .35); }
    .iotab-hero-art { flex: none; inline-size: 56px; aspect-ratio: 1; border-radius: 10px; background: linear-gradient(135deg, #ffffffd9, #ffffff33); }
    .iotab-hero b { display: block; font-size: 1rem; } .iotab-hero span { font-size: .8rem; opacity: .85; }
    .iotab-hero button { margin-inline-start: auto; display: grid; place-items: center; inline-size: 40px; aspect-ratio: 1; border-radius: 50%;
                         border: 0; padding: 0; background: rgba(255, 255, 255, .22); color: #fff; transition: scale .12s ease; }
    .iotab-hero button:active { scale: .92; }
    .iotab-sec { margin: 1.1rem 0 .5rem; font: 600 .95rem/-apple-system, system-ui, sans-serif; }
    .iotab-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .7rem; }
    .iotab-tile { aspect-ratio: 1.35; display: grid; align-content: end; padding: .6rem .7rem; border-radius: 12px; color: #fff; font-size: .82rem; font-weight: 600; }
    .iotab-tile:nth-child(1) { background: linear-gradient(135deg, #FF9500, #FF3B30 80%); }
    .iotab-tile:nth-child(2) { background: linear-gradient(135deg, #30D158, #007AFF 85%); }
    .iotab-tile:nth-child(3) { background: linear-gradient(135deg, #64D2FF, #5856D6 85%); }
    .iotab-tile:nth-child(4) { background: linear-gradient(135deg, #FF3B75, #FF9F0A 90%); }
    .iotab-song { display: flex; align-items: center; gap: .7rem; padding: .55rem .2rem; }
    .iotab-song + .iotab-song { border-block-start: .5px solid var(--ios-sep); }
    .iotab-song-art { flex: none; inline-size: 42px; aspect-ratio: 1; border-radius: 8px; }
    .iotab-song b { display: block; font-size: .9rem; font-weight: 600; } .iotab-song span { font-size: .78rem; color: var(--ios-label-2); }
    .iotab-song time { margin-inline-start: auto; font-size: .78rem; color: var(--ios-label-2); font-variant-numeric: tabular-nums; }
    .iotab-home { position: absolute; inset-inline: 0; inset-block-end: .45rem; margin: auto; inline-size: 34%; block-size: 5px; border-radius: 999px; background: var(--ios-label); opacity: .8; z-index: 1; }

    .iotab-bar { position: absolute; inset-inline: 1rem; inset-block-end: 1.5rem; z-index: 2; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
                 padding: .35rem; border-radius: 999px; background: var(--iotab-glass);
                 backdrop-filter: blur(24px) saturate(1.8); -webkit-backdrop-filter: blur(24px) saturate(1.8);
                 box-shadow: 0 8px 32px rgba(0, 0, 0, .22), inset 0 0 0 .5px rgba(255, 255, 255, .8);
                 transition: background .3s ease, box-shadow .3s ease; }
    .iotab-bar::before { content: ''; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
                         background: linear-gradient(180deg, rgba(255, 255, 255, .5), rgba(255, 255, 255, 0) 55%); }
    .iotab-bar[data-variant="clear"] { background: var(--iotab-glass-clear);
                 backdrop-filter: blur(6px) saturate(1.4); -webkit-backdrop-filter: blur(6px) saturate(1.4);
                 box-shadow: 0 8px 32px rgba(0, 0, 0, .3), inset 0 0 0 .5px rgba(255, 255, 255, .35); }
    .iotab-bar[data-variant="clear"]::before { background: linear-gradient(180deg, rgba(255, 255, 255, .22), rgba(255, 255, 255, 0) 55%); }
    .iotab-drop { position: absolute; inset-block: .35rem; inset-inline-start: .35rem; inline-size: calc((100% - .7rem) / 4); border-radius: 999px;
                  background: var(--iotab-tint-soft); transition: translate .55s cubic-bezier(.32, .72, .28, 1.18); }
    .iotab-item { position: relative; z-index: 1; display: grid; place-items: center; gap: 1px; block-size: 46px; border-radius: 999px;
                  border: 0; padding: 0; font: inherit;
                  color: var(--ios-gray); font-size: .6rem; font-weight: 500; transition: color .25s ease, scale .12s ease; }
    .iotab-item svg { inline-size: 21px; block-size: 21px; }
    .iotab-item > span { max-inline-size: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
    .iotab-item:active { scale: .94; }
    .iotab-item[aria-current="page"] { color: var(--ios-blue); }
    .iotab-badge { position: absolute; inset-block-start: 3px; inset-inline-end: 24%; min-inline-size: 1.05rem; padding-inline: .22rem; block-size: 1.05rem;
                   display: grid; place-items: center; border-radius: 999px; background: var(--ios-red); color: #fff; font-size: .62rem; font-weight: 700;
                   box-shadow: 0 0 0 2px var(--iotab-glass); }
    .iotab-toggle { display: inline-flex; align-items: center; gap: .45rem; padding: .45rem .9rem; border-radius: 999px; border: 0;
                    background: var(--ios-fill); color: inherit; font: inherit; font-size: .85rem; font-weight: 500; transition: scale .12s ease; }
    .iotab-toggle:active { scale: .96; }
    .iotab-toggle .knob { position: relative; inline-size: 34px; block-size: 20px; border-radius: 999px; background: var(--ios-label-3); transition: background .25s ease; }
    .iotab-toggle .knob::after { content: ''; position: absolute; inset-block-start: 2px; inset-inline-start: 2px; inline-size: 16px; aspect-ratio: 1;
                    border-radius: 50%; background: #fff; transition: translate .3s cubic-bezier(.3, .9, .4, 1.15); }
    .iotab-toggle[aria-pressed="true"] .knob { background: var(--ios-green); }
    .iotab-toggle[aria-pressed="true"] .knob::after { translate: 14px 0; }
    [dir="rtl"] .iotab-toggle[aria-pressed="true"] .knob::after { translate: -14px 0; }

    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. This partial ships only with this one
       page, so pin its rows visible here. (The clean fix would be dropping
       data-nx-reveal from the table in livewire/component-demo.blade.php.) */
    html:has(.iotab-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }

    @media (prefers-reduced-motion: reduce) {
        .iotab-drop, .iotab-toggle .knob, .iotab-item, .iotab-toggle { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The tab bar that floats like glass', 'تب‌باری که مثل شیشه شناور است') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A liquid-glass pill floats over the music app with a lit rim and refracted light; the selection rolls between tabs like a droplet on a spring, the red badge counts your unread messages, and tapping the current tab scrolls the stage back to the top. The toggle switches to the fully translucent «clear» recipe.', 'قرص شیشهٔ مایع روی اپ موسیقی شناور است؛ لبهٔ نورانی و شکست نور دارد، انتخاب مثل یک قطره با فنر بین تب‌ها می‌غلتد، نشان قرمز پیام‌های نخوانده را می‌شمارد، و لمس دوبارهٔ تب فعال محتوا را به بالای صحنه برمی‌گرداند. کلید پایین، نسخهٔ کاملاً شفاف «clear» را روشن می‌کند.') }}
        </p>
    </div>

    <div class="iotab-root" x-data="{
            rtl: document.documentElement.dir === 'rtl',
            cur: 0, clear: false, playing: false,
            tabs: [
                { en: 'Home', fa: 'خانه' },
                { en: 'Search', fa: 'جست‌وجو' },
                { en: 'Messages', fa: 'پیام‌ها' },
                { en: 'Library', fa: 'کتابخانه' },
            ],
            pick(i) { if (this.cur === i) { this.$refs.body.scrollTo({ top: 0, behavior: 'smooth' }) } else { this.cur = i } },
            dropOff() { return (this.rtl ? -this.cur : this.cur) * 100 },
        }">
        <div class="iotab-phone">
            <div class="iotab-screen">
                <span class="iotab-pill" aria-hidden="true"></span>
                <div class="iotab-status" aria-hidden="true">
                    <b>{{ $say('9:41', '۰۹:۴۱') }}</b>
                    <span class="iotab-status-end">
                        <span class="bars"><i></i><i></i><i></i><i></i></span>
                        <span class="iotab-batt"></span>
                    </span>
                </div>

                <div class="iotab-body" x-ref="body">
                    <h4 class="iotab-hello">{{ $say('Good evening, Arash', 'عصر بخیر، آرش') }}</h4>
                    <div class="iotab-hero">
                        <span class="iotab-hero-art" aria-hidden="true"></span>
                        <div>
                            <b>{{ $say('Tehran Alleys', 'کوچه‌های تهران') }}</b>
                            <span>{{ $say('Bachar Choir — now playing', 'کر باچار — در حال پخش') }}</span>
                        </div>
                        <button type="button" x-on:click="playing = !playing" :aria-label="playing ? $say('Pause', 'توقف') : $say('Play', 'پخش')">
                            <svg x-show="!playing" width="15" height="15" viewBox="0 0 15 15" fill="currentColor" aria-hidden="true"><path d="M4 2.5v10l9-5z"/></svg>
                            <svg x-show="playing" x-cloak width="15" height="15" viewBox="0 0 15 15" fill="currentColor" aria-hidden="true"><rect x="3" y="2" width="3.2" height="11" rx="1"/><rect x="8.8" y="2" width="3.2" height="11" rx="1"/></svg>
                        </button>
                    </div>

                    <h5 class="iotab-sec">{{ $say('Recently played', 'پخش‌شده‌های اخیر') }}</h5>
                    <div class="iotab-grid">
                        <div class="iotab-tile">{{ $say('Bandari Nights', 'شب‌های بندری') }}</div>
                        <div class="iotab-tile">{{ $say('Tehran Rain', 'باران تهران') }}</div>
                        <div class="iotab-tile">{{ $say('Crossroads', 'چهارراه') }}</div>
                        <div class="iotab-tile">{{ $say('Alley No. 24', 'کوچهٔ ۲۴') }}</div>
                    </div>

                    <h5 class="iotab-sec">{{ $say('Made for you', 'ساخته‌شده برای شما') }}</h5>
                    <div>
                        <div class="iotab-song">
                            <span class="iotab-song-art" style="background: linear-gradient(135deg, #FF9500, #FF3B30)" aria-hidden="true"></span>
                            <div><b>{{ $say('Rainy Night', 'شبِ باران') }}</b><span>{{ $say('Homa & Reza', 'هما و رضا') }}</span></div>
                            <time>۳:۴۵</time>
                        </div>
                        <div class="iotab-song">
                            <span class="iotab-song-art" style="background: linear-gradient(135deg, #30D158, #007AFF)" aria-hidden="true"></span>
                            <div><b>{{ $say('Southern Sea', 'دریای جنوب') }}</b><span>{{ $say('Bandari Nights', 'شب‌های بندری') }}</span></div>
                            <time>۴:۱۲</time>
                        </div>
                        <div class="iotab-song">
                            <span class="iotab-song-art" style="background: linear-gradient(135deg, #64D2FF, #5856D6)" aria-hidden="true"></span>
                            <div><b>{{ $say('Morning of Pardis', 'صبح پردیس') }}</b><span>{{ $say('Sara Ahmadi', 'سارا احمدی') }}</span></div>
                            <time>۲:۵۸</time>
                        </div>
                    </div>
                </div>

                <span class="iotab-home" aria-hidden="true"></span>

                <nav class="iotab-bar" :data-variant="clear ? 'clear' : 'regular'" :aria-label="$say('Tab bar', 'تب‌بار')">
                    <span class="iotab-drop" aria-hidden="true" :style="'translate: ' + dropOff() + '% 0'"></span>
                    @foreach (explode('|', $say('Home|Search|Messages|Library', 'خانه|جست‌وجو|پیام‌ها|کتابخانه')) as $i => $label)
                        <button type="button" class="iotab-item" x-on:click="pick({{ $i }})" :aria-current="cur === {{ $i }} ? 'page' : null"
                                @if ($i === 2) :aria-label="$say('Messages, 3 unread', 'پیام‌ها، ۳ نخوانده')" @endif>
                            @if ($i === 0)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3.5 10.5 12 3.5l8.5 7v9a1.5 1.5 0 0 1-1.5 1.5h-4.5v-6h-5v6H5a1.5 1.5 0 0 1-1.5-1.5Z"/></svg>
                            @elseif ($i === 1)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m20 20-4.8-4.8"/></svg>
                            @elseif ($i === 2)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3.5c5 0 8.5 3.4 8.5 7.7S17 18.9 12 18.9c-1 0-2-.1-2.9-.4L4.5 20l1.2-3.6A7.6 7.6 0 0 1 3.5 11.2C3.5 6.9 7 3.5 12 3.5Z"/></svg>
                                <span class="iotab-badge">{{ $say('3', '۳') }}</span>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V6.5L20 4v11.5"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="17.5" cy="15.5" r="2.5"/></svg>
                            @endif
                            <span>{{ $label }}</span>
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <div style="display: flex; justify-content: center; margin-block-start: 1.1rem">
            <button type="button" class="iotab-toggle" x-on:click="clear = !clear" :aria-pressed="clear">
                <span class="knob" aria-hidden="true"></span>
                {{ $say('Clear variant', 'نسخهٔ clear') }}
            </button>
        </div>
    </div>
</section>
