{{--
    The testimonials section on its warm palette: a big-quote carousel the
    team can drive with buttons, dots or the arrow keys, over a grid of
    short star-rated quotes. Slides stack in one grid area so the stage's
    height never jumps between quotes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $quotes = [
        ['initials' => 'AL', 'hue' => 'amber',
         'text' => $say('The week we adopted Beacon we retired three tools. The billing page nobody dared to touch finally has an owner.', 'هفته‌ای که بیکِن را آوردیم، سه ابزار را بازنشسته کردیم. صفحهٔ پرداختی که هیچ‌کس جرئت دست‌زدن به آن را نداشت، بالاخره صاحب گرفت.'),
         'name' => $say('Amelie Laurent', 'املی لوران'), 'role' => $say('VP Engineering · Northwind, Oslo', 'معاون مهندسی · نورت‌ویند، اسلو')],
        ['initials' => 'DF', 'hue' => 'moss',
         'text' => $say('The audit log paid for itself in one incident — we traced a broken deploy to a single env var in four minutes.', 'گزارش حسابرسی همان هفتهٔ اول جایش را پر کرد — یک استقرار خراب را در چهار دقیقه تا یک متغیر محیطی ردیابی کردیم.'),
         'name' => $say('Diego Ferreira', 'دیگو فرریرا'), 'role' => $say('SRE Lead · Helios Labs, Lisbon', 'راهبر SRE · هلیوس لبز، لیسبون')],
        ['initials' => 'MT', 'hue' => 'clay',
         'text' => $say('Onboarding new engineers dropped from two weeks to three days. They just read the runbooks.', 'آماده‌سازی مهندسان تازه از دو هفته به سه روز رسید. فقط ران‌بوک‌ها را می‌خوانند.'),
         'name' => $say('Mei Tanaka', 'می تاناکا'), 'role' => $say('CTO · Quantia, Singapore', 'مدیر فنی · کوآنتیا، سنگاپور')],
    ];

    $shorts = [
        ['initials' => 'PS', 'stars' => 5, 'text' => $say('Support answers in minutes, not days.', 'پشتیبانی در چند دقیقه جواب می‌دهد، نه چند روز.'),
         'name' => $say('Priya Sharma', 'پریا شارما'), 'co' => $say('Fabrik · Toronto', 'فابریک · تورنتو')],
        ['initials' => 'JW', 'stars' => 5, 'text' => $say('The changelog writes itself now.', 'حالا گزارش تغییرات خودش نوشته می‌شود.'),
         'name' => $say('Jonas Weber', 'یوناس وبر'), 'co' => $say('Kanso · Berlin', 'کانسو · برلین')],
        ['initials' => 'SR', 'stars' => 4, 'text' => $say('A dashboard the whole team actually opens.', 'داشبوردی که کل تیم واقعاً باز می‌کند.'),
         'name' => $say('Sofia Rossi', 'سوفیا روسی'), 'co' => $say('Vantage · Milan', 'وانتاژ · میلان')],
        ['initials' => 'AK', 'stars' => 5, 'text' => $say('Zero training day. The team just used it.', 'بدون روز آموزش. تیم فقط ازش استفاده کرد.'),
         'name' => $say('Alex Kim', 'الکس کیم'), 'co' => $say('Orbital · Seoul', 'اوربیتال · سئول')],
    ];
@endphp
<style>
    .sectm-root {
        --sectm-page: #FBF7F0; --sectm-card: #FFFCF6; --sectm-border: #EADFCB;
        --sectm-text: #292524; --sectm-muted: #79716B; --sectm-accent: #B45309;
        --sectm-star: #F59E0B; --sectm-chip: #F3E8D7;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--sectm-text);
        display: grid;
        gap: 2rem;
    }
    html[data-theme="dark"] .sectm-root {
        --sectm-page: #201B15; --sectm-card: #2A241D; --sectm-border: #3B332A;
        --sectm-text: #FAF6EF; --sectm-muted: #B8AFA4; --sectm-accent: #F59E0B;
        --sectm-star: #FBBF24; --sectm-chip: #352E25;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .sectm-root {
            --sectm-page: #201B15; --sectm-card: #2A241D; --sectm-border: #3B332A;
            --sectm-text: #FAF6EF; --sectm-muted: #B8AFA4; --sectm-accent: #F59E0B;
            --sectm-star: #FBBF24; --sectm-chip: #352E25;
        }
    }
    .sectm {
        inline-size: 100%; padding: clamp(2rem, 5vw, 3.75rem) clamp(1.25rem, 4vw, 2.5rem);
        border: 1px solid var(--sectm-border); border-radius: 1.4rem; background: var(--sectm-page);
        display: grid; gap: 2rem; justify-items: center; overflow: clip;
    }
    .sectm-head { display: grid; gap: .7rem; justify-items: center; text-align: center; }
    .sectm-score { display: inline-flex; align-items: center; gap: .55rem; padding: .35rem .9rem;
                   border: 1px solid var(--sectm-border); border-radius: 999px; background: var(--sectm-card);
                   font-size: .76rem; font-weight: 600; color: var(--sectm-muted); }
    .sectm-score svg { inline-size: .95rem; block-size: .95rem; fill: var(--sectm-star); }
    .sectm-head h3 { margin: 0; font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.25; }
    .sectm-head p { margin: 0; max-inline-size: 46ch; font-size: .92rem; line-height: 1.75; color: var(--sectm-muted); }

    .sectm-stage { display: grid; inline-size: 100%; max-inline-size: 46rem; }
    .sectm-quote { grid-area: 1 / 1; margin: 0; display: grid; gap: 1.4rem; justify-items: center;
                   text-align: center; transition: opacity .5s ease, translate .5s ease; }
    .sectm-quote[data-off="true"] { opacity: 0; pointer-events: none; translate: 0 .75rem; }
    .sectm-mark { inline-size: 2.1rem; block-size: 2.1rem; stroke: var(--sectm-accent); stroke-width: 2;
                  fill: none; stroke-linecap: round; stroke-linejoin: round; opacity: .9; }
    .sectm-quote > p { margin: 0; font-size: clamp(1.12rem, 2.6vw, 1.55rem); font-weight: 650;
                       line-height: 1.75; letter-spacing: -.01em; max-inline-size: 40ch; }
    .sectm-who { display: inline-flex; align-items: center; gap: .8rem; text-align: start; }
    .sectm-avatar { display: inline-grid; place-items: center; inline-size: 2.85rem; aspect-ratio: 1;
                    border-radius: 999px; color: #FFFCF6; font-size: .85rem; font-weight: 700; letter-spacing: .02em; }
    .sectm-avatar[data-hue="amber"] { background: #B45309; }
    .sectm-avatar[data-hue="moss"] { background: #3F6212; }
    .sectm-avatar[data-hue="clay"] { background: #9A3412; }
    .sectm-who b { display: block; font-size: .92rem; }
    .sectm-who small { color: var(--sectm-muted); font-size: .78rem; }

    .sectm-nav { display: flex; align-items: center; gap: .9rem; }
    .sectm-nav button { border: none; cursor: pointer; background: transparent; }
    .sectm-arrow { display: inline-grid; place-items: center; inline-size: 2.3rem; aspect-ratio: 1;
                   border-radius: 999px; background: var(--sectm-chip); color: var(--sectm-text);
                   transition: background-color .2s ease, scale .2s ease; }
    .sectm-arrow svg { inline-size: 1rem; block-size: 1rem; stroke: currentColor; stroke-width: 2.25;
                       fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .sectm-arrow:hover { scale: 1.06; }
    .sectm-arrow:active { scale: .96; }
    .sectm-dots { display: flex; align-items: center; gap: .45rem; }
    .sectm-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 999px; background: var(--sectm-border);
                 transition: inline-size .25s ease, background-color .25s ease; }
    .sectm-dot[data-on="true"] { inline-size: 1.3rem; background: var(--sectm-accent); }
    .sectm-arrow:focus-visible, .sectm-dot:focus-visible { outline: 2px solid var(--sectm-accent); outline-offset: 2px; }
    .sectm-hint { margin: 0; font-size: .72rem; color: var(--sectm-muted); }

    .sectm-shorts { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr));
                    inline-size: 100%; max-inline-size: 62rem; }
    .sectm-short { margin: 0; display: grid; gap: .55rem; align-content: start;
                   padding: 1.25rem 1.15rem; border: 1px solid var(--sectm-border); border-radius: 1.15rem;
                   background: var(--sectm-card); }
    .sectm-stars { display: inline-flex; gap: .15rem; }
    .sectm-stars svg { inline-size: .9rem; block-size: .9rem; }
    .sectm-stars svg[data-on="true"] { fill: var(--sectm-star); }
    .sectm-stars svg[data-on="false"] { fill: none; stroke: var(--sectm-border); stroke-width: 1.5; }
    .sectm-short blockquote { margin: 0; font-size: .88rem; font-weight: 600; line-height: 1.65; }
    .sectm-short figcaption { display: flex; align-items: center; gap: .5rem; margin-block-start: .35rem;
                              font-size: .76rem; color: var(--sectm-muted); }
    .sectm-mini { display: inline-grid; place-items: center; inline-size: 1.7rem; aspect-ratio: 1;
                  border-radius: 999px; background: var(--sectm-chip); color: var(--sectm-text);
                  font-size: .62rem; font-weight: 700; }
    @media (max-width: 860px) { .sectm-shorts { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 480px) { .sectm-shorts { grid-template-columns: 1fr; } }
    @media (prefers-reduced-motion: reduce) {
        .sectm-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="sectm-root"
    x-data="{
        i: 0,
        count: {{ count($quotes) }},
        step(d) { this.i = (this.i + d + this.count) % this.count; },
    }"
    x-on:keydown.window.arrow-right.prevent="step(1)"
    x-on:keydown.window.arrow-left.prevent="step(-1)">
    <section class="sectm" aria-labelledby="sectm-title">
        <header class="sectm-head">
            <span class="sectm-score">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.5l2.95 5.98 6.6.96-4.78 4.66 1.13 6.57L12 17.56l-5.9 3.11 1.13-6.57-4.78-4.66 6.6-.96L12 2.5z"/></svg>
                {{ $num('4.9') }} {{ $say('/ 5 across 2,400+ reviews on G2', 'از ۵ در بیش از ۲٬۴۰۰ نظر در G2') }}
            </span>
            <h3 id="sectm-title">{{ $say('Teams don’t leave — they write about it', 'تیم‌ها نمی‌روند — درباره‌اش می‌نویسند') }}</h3>
            <p>{{ $say('From Oslo to Singapore, the story repeats: less busywork, clearer ownership, faster mornings.', 'از اسلو تا سنگاپور داستان تکرار می‌شود: کار اضافهٔ کمتر، مالکیت روشن‌تر، صبح‌های سریع‌تر.') }}</p>
        </header>

        <div class="sectm-stage" role="group" aria-label="{{ $say('Customer quotes', 'نقل‌قول‌های مشتریان') }}" aria-live="polite">
            @foreach ($quotes as $n => $quote)
                <blockquote class="sectm-quote" :data-off="String(i !== {{ $n }})">
                    <svg class="sectm-mark" viewBox="0 0 24 24" aria-hidden="true"><path d="M9.5 6C6.5 7.5 5 9.8 5 13v5h5v-6H7.6c.2-1.8 1.1-3.1 2.7-4L9.5 6zm9 0c-3 1.5-4.5 3.8-4.5 7v5h5v-6h-2.4c.2-1.8 1.1-3.1 2.7-4L18.5 6z"/></svg>
                    <p>{{ $quote['text'] }}</p>
                    <footer class="sectm-who">
                        <span class="sectm-avatar" data-hue="{{ $quote['hue'] }}" aria-hidden="true">{{ $quote['initials'] }}</span>
                        <span><b>{{ $quote['name'] }}</b><small>{{ $quote['role'] }}</small></span>
                    </footer>
                </blockquote>
            @endforeach
        </div>

        <div class="sectm-nav">
            <button type="button" class="sectm-arrow" x-on:click="step(-1)" aria-label="{{ $say('Previous quote', 'نقل‌قول قبلی') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.5 5.5L8 12l6.5 6.5"/></svg>
            </button>
            <div class="sectm-dots">
                @foreach ($quotes as $n => $quote)
                    <button type="button" class="sectm-dot" :data-on="String(i === {{ $n }})"
                        x-on:click="i = {{ $n }}" aria-label="{{ $say('Quote '.($n + 1), 'نقل‌قول '.$num((string) ($n + 1))) }}"></button>
                @endforeach
            </div>
            <button type="button" class="sectm-arrow" x-on:click="step(1)" aria-label="{{ $say('Next quote', 'نقل‌قول بعدی') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.5 5.5L16 12l-6.5 6.5"/></svg>
            </button>
        </div>
        <p class="sectm-hint">{{ $say('Tip: the ← → keys work too.', 'نکته: کلیدهای ← → هم کار می‌کنند.') }}</p>

        <div class="sectm-shorts">
            @foreach ($shorts as $short)
                <figure class="sectm-short">
                    <span class="sectm-stars" role="img" aria-label="{{ $say($short['stars'].' of 5 stars', $num((string) $short['stars']).' از ۵ ستاره') }}">
                        @foreach (range(1, 5) as $star)
                            <svg viewBox="0 0 24 24" data-on="{{ $star <= $short['stars'] ? 'true' : 'false' }}" aria-hidden="true">
                                <path d="M12 2.5l2.95 5.98 6.6.96-4.78 4.66 1.13 6.57L12 17.56l-5.9 3.11 1.13-6.57-4.78-4.66 6.6-.96L12 2.5z"/>
                            </svg>
                        @endforeach
                    </span>
                    <blockquote>{{ $short['text'] }}</blockquote>
                    <figcaption>
                        <span class="sectm-mini" aria-hidden="true">{{ $short['initials'] }}</span>
                        <span><b style="color: var(--sectm-text)">{{ $short['name'] }}</b> · {{ $short['co'] }}</span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
</div>
