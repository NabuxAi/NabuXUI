{{--
    Sonoma desktop widgets on a gradient wallpaper: a small analog clock
    whose CSS hands rotate with real time from an Alpine Date and tick each
    second (static under reduced motion), a medium Tehran weather widget
    with an hourly strip, a small calendar with today highlighted, and a
    medium music player with a working play/pause toggle and a progress bar
    that animates while playing. Rounded 22, soft shadows, dark glass that
    flips with the page theme.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $hours = [
        ['fa' => 'اکنون', 'en' => 'Now', 'icon' => '⛅', 'temp' => '۲۸°'],
        ['fa' => '۱۴', 'en' => '2 PM', 'icon' => '☀️', 'temp' => '۲۹°'],
        ['fa' => '۱۵', 'en' => '3 PM', 'icon' => '☀️', 'temp' => '۳۰°'],
        ['fa' => '۱۶', 'en' => '4 PM', 'icon' => '🌤', 'temp' => '۲۹°'],
        ['fa' => '۱۷', 'en' => '5 PM', 'icon' => '🌥', 'temp' => '۲۷°'],
    ];
@endphp
<style>
    .mcwdg-root {
        --mcwdg-text: #1E1E1E; --mcwdg-text2: rgba(30, 30, 30, .6); --mcwdg-accent: #007AFF;
        --mcwdg-glass-a: rgba(255, 255, 255, .74); --mcwdg-glass-b: rgba(255, 255, 255, .52);
        --mcwdg-line: rgba(0, 0, 0, .1);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcwdg-root {
        --mcwdg-text: #F5F5F5; --mcwdg-text2: rgba(245, 245, 245, .62); --mcwdg-accent: #0A84FF;
        --mcwdg-glass-a: rgba(255, 255, 255, .16); --mcwdg-glass-b: rgba(255, 255, 255, .07);
        --mcwdg-line: rgba(255, 255, 255, .24);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcwdg-root {
            --mcwdg-text: #F5F5F5; --mcwdg-text2: rgba(245, 245, 245, .62); --mcwdg-accent: #0A84FF;
            --mcwdg-glass-a: rgba(255, 255, 255, .16); --mcwdg-glass-b: rgba(255, 255, 255, .07);
            --mcwdg-line: rgba(255, 255, 255, .24);
        }
    }

    .mcwdg-stage {
        position: relative; overflow: clip; padding: 2.25rem 1.25rem; border-radius: var(--nx-radius-2xl);
        background: linear-gradient(145deg, #22335E 0%, #7A4E7E 55%, #E8956D 100%);
        display: flex; flex-wrap: wrap; gap: 16px; justify-content: center; align-items: center;
    }
    .mcwdg-stage::before { content: ''; position: absolute; inset: 0; background: radial-gradient(60% 70% at 70% 20%, rgba(255, 175, 120, .35), transparent 70%); pointer-events: none; }

    .mcwdg {
        position: relative; z-index: 1; display: grid; gap: 8px; align-content: start;
        padding: 14px; border-radius: 22px; overflow: clip;
        color: var(--mcwdg-text);
        background: linear-gradient(150deg, var(--mcwdg-glass-a), var(--mcwdg-glass-b));
        backdrop-filter: blur(30px) saturate(1.5);
        box-shadow: 0 12px 34px rgba(0, 0, 0, .28), inset 0 0 0 .5px var(--mcwdg-line);
        max-inline-size: 100%;
    }
    .mcwdg[data-size='small'] { inline-size: 152px; }
    .mcwdg[data-size='medium'] { inline-size: min(320px, 100%); }
    .mcwdg b { font: 600 13px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcwdg small { color: var(--mcwdg-text2); font-size: 11px; }
    .mcwdg .digits { font-variant-numeric: tabular-nums; }

    .mcwdg-dial { position: relative; inline-size: 104px; aspect-ratio: 1; margin: 2px auto; border-radius: 50%; background: radial-gradient(circle at 50% 38%, color-mix(in srgb, var(--mcwdg-glass-a) 60%, transparent), color-mix(in srgb, var(--mcwdg-glass-b) 80%, transparent)); box-shadow: inset 0 0 0 1px var(--mcwdg-line); }
    .mcwdg-dial i { position: absolute; inline-size: 3px; border-radius: 3px; background: var(--mcwdg-text); transform-origin: 50% 100%; }
    .mcwdg-dial i[data-h] { inset-block-start: calc(50% - 26%); inset-inline-start: calc(50% - 1.5px); block-size: 26%; rotate: var(--mcwdg-h, 0deg); }
    .mcwdg-dial i[data-m] { inline-size: 2.5px; inset-block-start: calc(50% - 38%); inset-inline-start: calc(50% - 1.25px); block-size: 38%; rotate: var(--mcwdg-m, 0deg); }
    .mcwdg-dial i[data-s] { inline-size: 1.5px; inset-block-start: calc(50% - 42%); inset-inline-start: calc(50% - .75px); block-size: 42%; rotate: var(--mcwdg-s, 0deg); background: var(--mcwdg-accent); }
    .mcwdg-dial::after { content: ''; position: absolute; inset-block-start: calc(50% - 3px); inset-inline-start: calc(50% - 3px); inline-size: 6px; aspect-ratio: 1; border-radius: 50%; background: var(--mcwdg-text); }
    .mcwdg-clockline { text-align: center; }
    .mcwdg-clockline b { font-size: 20px; }

    .mcwdg-now { display: flex; align-items: center; gap: 12px; }
    .mcwdg-now b { font-size: 34px; font-variant-numeric: tabular-nums; }
    .mcwdg-strip { display: flex; justify-content: space-between; gap: 6px; }
    .mcwdg-strip div { display: grid; gap: 3px; justify-items: center; font-size: 11px; color: var(--mcwdg-text2); }
    .mcwdg-strip b { color: var(--mcwdg-text); font-weight: 500; font-variant-numeric: tabular-nums; }
    .mcwdg-strip i { font-style: normal; font-size: 14px; }

    .mcwdg-cal { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; justify-items: center; }
    .mcwdg-cal b { grid-column: 1 / -1; }
    .mcwdg-cal u { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; inline-size: 100%; text-decoration: none; }
    .mcwdg-cal s, .mcwdg-cal em { display: grid; place-items: center; inline-size: 17px; aspect-ratio: 1; font: 500 9.5px/1 system-ui, sans-serif; font-style: normal; text-decoration: none; color: var(--mcwdg-text2); }
    .mcwdg-cal em { color: var(--mcwdg-text); font-variant-numeric: tabular-nums; }
    .mcwdg-cal em[data-today] { background: var(--mcwdg-accent); color: #fff; border-radius: 50%; font-weight: 700; }

    .mcwdg-player { display: grid; grid-template-columns: 44px minmax(0, 1fr); gap: 10px; align-items: center; }
    .mcwdg-art { display: grid; place-items: center; inline-size: 44px; aspect-ratio: 1; border-radius: 10px; background: linear-gradient(150deg, #FC6076, #7A2BD0); font-size: 19px; box-shadow: 0 4px 12px rgba(0, 0, 0, .3); }
    .mcwdg-meta { min-inline-size: 0; }
    .mcwdg-meta b { display: block; white-space: nowrap; overflow: clip; text-overflow: ellipsis; }
    .mcwdg-ctl { grid-column: 1 / -1; display: flex; align-items: center; gap: 8px; }
    .mcwdg-play {
        display: grid; place-items: center; inline-size: 28px; aspect-ratio: 1; padding: 0; flex: none;
        border: 0; border-radius: 50%; cursor: pointer;
        background: var(--mcwdg-accent); color: #fff;
    }
    .mcwdg-play svg { inline-size: 12px; block-size: 12px; fill: currentColor; }
    .mcwdg-track { position: relative; flex: 1; block-size: 4px; border-radius: 2px; background: color-mix(in srgb, var(--mcwdg-text2) 40%, transparent); }
    .mcwdg-track i { position: absolute; inset-block: 0; inset-inline-start: 0; border-radius: 2px; background: var(--mcwdg-text); transition: inline-size 1s linear; }
    .mcwdg-time { font-size: 10.5px; color: var(--mcwdg-text2); font-variant-numeric: tabular-nums; }
    .mcwdg-root :is(button):focus-visible { outline: 2px solid var(--mcwdg-accent); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .mcwdg-track i { transition: none; } }

    /* Demo-page chrome repair, scoped to this page's props table and snippet.
       The shared data-table keeps its rows unpainted until a scroll observer
       reveals them — that observer never fires in a full-page capture, so the
       Important-props table reads as a bare header. Keep the rows painted.
       On narrow screens the nowrap cells and the sideways snippet scroller
       read as cut-off columns, so let both wrap inside the box instead. */
    section[aria-labelledby="demo-props-title"] .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 48rem) {
        section[aria-labelledby="demo-props-title"] .nx-data-table :is(th, td) { white-space: normal; }
        section[aria-labelledby="demo-snippet-title"] pre { white-space: pre-wrap; overflow-wrap: break-word; }
    }
</style>

<section class="pg-box mcwdg-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Desktop widgets', 'ویجت‌های رومیزی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The clock really ticks and the music really plays — press play and watch the progress bar move. The calendar always highlights today, and the glass flips with the theme.', 'ساعت واقعاً تیک می‌زند و موسیقی واقعاً پخش می‌شود — پخش را بزنید و نوار پیشرفت را تماشا کنید. تقویم همیشه امروزِ واقعی را نشان می‌کند و شیشه با تم عوض می‌شود.') }}
        </p>
    </div>

    <div class="mcwdg-stage"
         x-data="{
                fa: {{ $fa ? 'true' : 'false' }},
                now: new Date(), playing: false, pct: 36, tick: null,
                cells: [], today: 0, calLabel: '',
                init() {
                    this.buildCal();
                    if (! window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        this.tick = setInterval(() => {
                            this.now = new Date();
                            if (this.playing) this.pct = (this.pct + .9) % 100;
                        }, 1000);
                    }
                },
                destroy() { clearInterval(this.tick) },
                buildCal() {
                    const t = new Date(), y = t.getFullYear(), m = t.getMonth();
                    const offset = (new Date(y, m, 1).getDay() + 1) % 7;
                    const days = new Date(y, m + 1, 0).getDate();
                    this.cells = [...Array(offset).fill(0), ...Array.from({ length: days }, (_, i) => i + 1)];
                    this.today = t.getDate();
                    const names = this.fa
                        ? ['ژانویه', 'فوریه', 'مارس', 'آپریل', 'مه', 'ژوئن', 'ژوئیه', 'اوت', 'سپتامبر', 'اکتبر', 'نوامبر', 'دسامبر']
                        : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    this.calLabel = names[m] + ' ' + this.n2(y);
                },
                n2(v) { const s = String(v); return this.fa ? s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s },
                pad(v) { return this.n2(String(v).padStart(2, '0')) },
                get hh() { return this.pad(this.now.getHours()) },
                get mm() { return this.pad(this.now.getMinutes()) },
                get ss() { return this.pad(this.now.getSeconds()) },
                get hands() {
                    const h = this.now.getHours() % 12 * 30 + this.now.getMinutes() * .5;
                    return '--mcwdg-h:' + h + 'deg; --mcwdg-m:' + this.now.getMinutes() * 6 + 'deg; --mcwdg-s:' + this.now.getSeconds() * 6 + 'deg';
                },
                get songTime() {
                    const total = 232, cur = Math.round(this.pct / 100 * total);
                    return this.n2(Math.floor(cur / 60)) + ':' + this.pad(cur % 60) + ' / ' + (this.fa ? '۳:۵۲' : '3:52');
                },
                toggle() { this.playing = ! this.playing },
            }"
         :style="'--mcwdg-h:0deg'">
        <div class="mcwdg" data-size="small" role="timer" aria-label="{{ $say('Analog clock', 'ساعت آنالوگ') }}">
            <div class="mcwdg-dial" :style="hands" aria-hidden="true"><i data-h></i><i data-m></i><i data-s></i></div>
            <p class="mcwdg-clockline digits" style="margin: 0"><b x-text="hh + ':' + mm">۱۴:۰۵</b> <small x-text="ss">۰۵</small></p>
            <small style="text-align: center">{{ $say('Tehran', 'تهران') }}</small>
        </div>

        <div class="mcwdg" data-size="medium" aria-label="{{ $say('Weather', 'آب‌وهوا') }}">
            <div class="mcwdg-now">
                <span aria-hidden="true" style="font-size: 30px">🌤</span>
                <div>
                    <b>{{ $say('Tehran', 'تهران') }}</b>
                    <small>{{ $say('Sunny · feels like ۲۷°', 'آفتابی · دمای احساسی ۲۷°') }}</small>
                </div>
                <b class="digits" style="margin-inline-start: auto">۲۸°</b>
            </div>
            <div class="mcwdg-strip">
                @foreach ($hours as $h)
                    <div>
                        <span>{{ $say($h['en'], $h['fa']) }}</span>
                        <i aria-hidden="true">{{ $h['icon'] }}</i>
                        <b class="digits">{{ $h['temp'] }}</b>
                    </div>
                @endforeach
            </div>
            <small>{{ $say('A few hours ahead: warming up to ۳۰°', 'چند ساعت جلو: گرم می‌شود تا ۳۰°') }}</small>
        </div>

        <div class="mcwdg" data-size="small" :aria-label="calLabel">
            <div class="mcwdg-cal">
                <b x-text="calLabel">اکتبر ۲۰۲۶</b>
                <u aria-hidden="true">
                    @foreach (['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] as $d)
                        <s>{{ $d }}</s>
                    @endforeach
                </u>
                <u>
                    <template x-for="(d, i) in cells" :key="i">
                        <em x-text="d ? n2(d) : ''" :data-today="d === today ? '' : null"></em>
                    </template>
                </u>
            </div>
            <small style="text-align: center">{{ $say('Design review', 'بازبینی طراحی') }} · ۱۷:۰۰</small>
        </div>

        <div class="mcwdg" data-size="medium" aria-label="{{ $say('Now playing', 'در حال پخش') }}">
            <div class="mcwdg-player">
                <span class="mcwdg-art" aria-hidden="true">🎵</span>
                <div class="mcwdg-meta">
                    <b>{{ $say('Tehran Nights', 'شب‌های تهران') }}</b>
                    <small>Raha Yazdi — {{ $say('City of Lovers', 'شهر عاشق‌ها') }}</small>
                </div>
                <div class="mcwdg-ctl">
                    <button type="button" class="mcwdg-play" :aria-label="playing ? (fa ? 'توقف' : 'Pause') : (fa ? 'پخش' : 'Play')" x-on:click="toggle()">
                        <svg x-show="! playing" viewBox="0 0 12 12" aria-hidden="true"><path d="M2.5 1.2l8 4.8-8 4.8z"/></svg>
                        <svg x-cloak x-show="playing" viewBox="0 0 12 12" aria-hidden="true"><path d="M2 1.5h3v9H2zM7 1.5h3v9H7z"/></svg>
                    </button>
                    <span class="mcwdg-track" aria-hidden="true"><i :style="'inline-size:' + pct + '%'"></i></span>
                    <span class="mcwdg-time digits" x-text="songTime">۱:۲۴ / ۳:۵۲</span>
                </div>
            </div>
        </div>
    </div>
</section>
