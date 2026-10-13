{{--
    The stats hero as a real first screen: display title, then the inline
    email form — one pill holding input and button, with live validation
    that shakes on error and swaps to a confirmation on success. Below,
    three big odometer counters roll to their values (each digit a vertical
    reel of ten glyphs, staggered 120ms) beside the verified badge — all on
    a calm dotted background that makes the numbers feel sworn.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;

    $reelGlyphs = $fa
        ? ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹']
        : ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $stats = $fa
        ? [
            ['digits' => [9, 9, 9, 9], 'glue' => '٫', 'suffix' => '٪', 'label' => 'آپ‌تایم با SLA'],
            ['digits' => [4, 2], 'glue' => '', 'suffix' => 'ms', 'label' => 'تأخیر p50 از لبه'],
            ['digits' => [2, 4], 'glue' => '', 'suffix' => 'هزار', 'label' => 'انتشار در هر روز'],
        ]
        : [
            ['digits' => [9, 9, 9, 9], 'glue' => '.', 'suffix' => '%', 'label' => 'uptime SLA'],
            ['digits' => [4, 2], 'glue' => '', 'suffix' => 'ms', 'label' => 'p50 edge latency'],
            ['digits' => [2, 4], 'glue' => '', 'suffix' => 'k', 'label' => 'rollouts each day'],
        ];
@endphp
<style>
    .hrst-hero {
        --hrst-ink: #12152b; --hrst-muted: #575d85;
        --hrst-panel: #ffffff; --hrst-line: #e3e6f5;
        --hrst-accent: #0e7a5f; --hrst-dot: #c9cede;
        position: relative; overflow: clip;
        padding: clamp(3.5rem, 8vw, 6.5rem) 1.25rem;
        color: var(--hrst-ink); text-align: center;
        display: grid; gap: 1.4rem; justify-items: center;
        background-color: #f8f9fd;
        background-image:
            radial-gradient(52rem 20rem at 50% 0%, color-mix(in oklab, var(--hrst-accent) 8%, transparent), transparent 70%),
            radial-gradient(color-mix(in oklab, var(--hrst-dot) 65%, transparent) 1px, transparent 1.4px);
        background-size: auto, 1.4rem 1.4rem;
    }
    html[data-theme="dark"] .hrst-hero {
        --hrst-ink: #eef1ff; --hrst-muted: #aab3e8;
        --hrst-panel: #181c3a; --hrst-line: #2b3160;
        --hrst-accent: #3ecf8e; --hrst-dot: #3a4070;
        background-color: #0e1023;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hrst-hero {
            --hrst-ink: #eef1ff; --hrst-muted: #aab3e8;
            --hrst-panel: #181c3a; --hrst-line: #2b3160;
            --hrst-accent: #3ecf8e; --hrst-dot: #3a4070;
            background-color: #0e1023;
        }
    }
    .hrst-head { display: grid; gap: 1rem; justify-items: center; }
    .hrst-title { margin: 0; max-inline-size: 20ch; font: 800 clamp(2.3rem, 6.6vw, 4.4rem) / 1.07 var(--nx-font-display);
                  letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .hrst-sub { margin: 0; max-inline-size: 46ch; font-size: var(--nx-text-lg); color: var(--hrst-muted); text-wrap: pretty; }

    .hrst-wrap { display: grid; gap: .5rem; justify-items: center; }
    .hrst-pill { display: flex; flex-wrap: wrap; gap: .4rem; inline-size: min(100%, 26rem);
                 padding: .4rem; border-radius: 999px; background: var(--hrst-panel);
                 border: 1px solid var(--hrst-line);
                 box-shadow: 0 .8rem 2rem color-mix(in oklab, #0b1020 12%, transparent);
                 transition: border-color .2s ease; }
    .hrst-pill:focus-within { border-color: color-mix(in oklab, var(--hrst-accent) 55%, transparent); }
    .hrst-pill[data-bad] { border-color: #e5484d; }
    .hrst-pill input { flex: 1 1 10rem; min-inline-size: 0; border: none; outline: none; background: none;
                       padding: .55rem .9rem; color: inherit; font: 400 .95rem/1.3 inherit; }
    .hrst-pill input::placeholder { color: var(--hrst-muted); }
    .hrst-shake { animation: hrst-shake .4s ease; }
    @keyframes hrst-shake { 20% { translate: -.35rem 0; } 60% { translate: .35rem 0; } }
    .hrst-msg { margin: 0; min-block-size: 1.1em; font-size: var(--nx-text-xs); color: #e5484d; }
    .hrst-msg.ok { color: var(--hrst-accent); }
    .hrst-done { display: inline-flex; align-items: center; gap: .55rem; padding: .8rem 1.25rem;
                 border-radius: 999px; background: var(--hrst-panel); border: 1px solid var(--hrst-line);
                 font-size: var(--nx-text-sm); box-shadow: 0 .8rem 2rem color-mix(in oklab, #0b1020 10%, transparent); }
    .hrst-done svg { inline-size: 1.15rem; aspect-ratio: 1; color: var(--hrst-accent); }

    .hrst-stats { display: flex; flex-wrap: wrap; gap: 1.75rem 3.5rem; justify-content: center;
                  margin-block-start: 1rem; }
    .hrst-stat { display: grid; gap: .5rem; justify-items: center; }
    .hrst-num { display: inline-flex; align-items: baseline; gap: .06em; margin: 0;
                font: 800 clamp(2.7rem, 8vw, 4.6rem)/1 var(--nx-font-display);
                letter-spacing: -.02em; font-variant-numeric: tabular-nums; }
    .hrst-reel { display: inline-block; overflow: clip; block-size: 1.15em; align-self: center;
                 transition: translate 1.25s cubic-bezier(.16, 1, .3, 1); }
    .hrst-reel i { display: block; block-size: 1.15em; line-height: 1.15em; font-style: normal; text-align: center; }
    .hrst-glue, .hrst-suffix { align-self: center; }
    .hrst-glue { font-size: .55em; }
    .hrst-suffix { font-size: .38em; font-weight: 700; color: var(--hrst-muted); margin-inline-start: .35em; }
    .hrst-label { font: 600 var(--nx-text-xs)/1.4 var(--nx-font-mono); letter-spacing: .08em;
                  text-transform: uppercase; color: var(--hrst-muted); }
    .hrst-verify { display: inline-flex; align-items: center; gap: .45rem; margin-block-start: .75rem;
                   font-size: var(--nx-text-xs); color: var(--hrst-muted); }
    .hrst-verify svg { inline-size: .95rem; aspect-ratio: 1; color: var(--hrst-accent); }
    .hrst-replay { margin: 0; border: none; background: none; cursor: pointer; display: inline-flex;
                   align-items: center; gap: .4rem; padding: .4rem .8rem; border-radius: 999px;
                   font: 600 var(--nx-text-xs)/1 inherit; color: var(--hrst-muted);
                   border: 1px solid color-mix(in oklab, var(--hrst-ink) 15%, transparent);
                   transition: color .2s, border-color .2s; }
    .hrst-replay:hover { color: var(--hrst-ink); border-color: color-mix(in oklab, var(--hrst-ink) 32%, transparent); }
    .hrst-replay svg { inline-size: .8rem; aspect-ratio: 1; }
    @media (prefers-reduced-motion: reduce) {
        .hrst-reel { transition-duration: .01ms; }
        .hrst-shake { animation-duration: .01ms; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="hrst-hero"
        x-data="{
            email: '', done: false, shake: false, run: false,
            groups: {{ \Illuminate\Support\Js::from(array_map(fn ($s) => $s['digits'], $stats)) }},
            get bad() { return this.email.length > 0 && !this.email.includes('@') },
            submit() {
                if (this.email.includes('@')) { this.done = true; return; }
                this.shake = true;
                setTimeout(() => this.shake = false, 450);
            },
            replay() {
                this.run = false;
                setTimeout(() => this.run = true, 80);
            },
        }"
        x-init="setTimeout(() => run = true, 250)">
        <div class="hrst-head">
            <x-nx::badge tone="success" dot>{{ $say('Measured quarterly · Northgate Audit', 'اندازه‌گیری فصلی · ممیزی نورث‌گیت') }}</x-nx::badge>
            <h2 class="hrst-title">
                {{ $say('The numbers behind calm releases', 'عددهای پشتِ انتشارهای آرام') }}
            </h2>
            <p class="hrst-sub">
                {{ $say('Asterly runs the rollout layer for teams in 40+ countries — measured, audited and awake while you sleep.', 'استرلی لایهٔ انتشارِ تیم‌هایی در بیش از ۴۰ کشور را می‌چرخاند — اندازه‌گیری‌شده، ممتحن و بیدار تا وقتی تو خوابی.') }}
            </p>
        </div>

        <div class="hrst-wrap">
            <template x-if="!done">
                <form class="hrst-pill" novalidate
                    :class="shake && 'hrst-shake'"
                    :data-bad="bad || null"
                    x-on:submit.prevent="submit">
                    <input type="email" x-model="email" autocomplete="email" dir="ltr"
                        placeholder="{{ $say('you@team.com', 'you@team.com') }}"
                        :aria-label="{{ \Illuminate\Support\Js::from($say('Work email', 'ایمیل کاری'))->toHtml() }}">
                    <x-nx::button variant="primary" shape="pill" icon-end="arrow-right" type="submit">
                        {{ $say('Start free', 'شروع رایگان') }}
                    </x-nx::button>
                </form>
            </template>
            <template x-if="done">
                <p class="hrst-done" role="status">
                    {!! \NabuXUI\NabuXUI::icon('check-circle') !!}
                    {{ $say('You’re on the list — see you in the inbox.', 'در فهرستی — به امید دیدار در ایمیل.') }}
                </p>
            </template>
            <p class="hrst-msg" :class="done && 'ok'"
                x-text="done ? {{ \Illuminate\Support\Js::from($say('Invite sent — check your spam too, just in case.', 'دعوت رفت — اسپم را هم نگاهی بینداز، محض احتیاط.'))->toHtml() }}
                    : bad ? {{ \Illuminate\Support\Js::from($say('That address is missing an @', 'این نشانی یک @ کم دارد'))->toHtml() }}
                    : ''"></p>
        </div>

        <div class="hrst-stats" dir="ltr">
            @foreach ($stats as $g => $stat)
                <div class="hrst-stat">
                    <b class="hrst-num">
                        @foreach ($stat['digits'] as $i => $digit)
                            <span class="hrst-reel"
                                :style="`translate: 0 calc(1.15em * ${-(run ? groups[{{ $g }}][{{ $i }}] : 0)}); transition-delay: ${({{ $i }}) * 120}ms`">
                                @foreach ($reelGlyphs as $glyph)
                                    <i>{{ $glyph }}</i>
                                @endforeach
                            </span>
                            @if ($i === 0 && $stat['glue'] !== '')
                                <span class="hrst-glue" aria-hidden="true">{{ $stat['glue'] }}</span>
                            @endif
                        @endforeach
                        <span class="hrst-suffix">{{ $stat['suffix'] }}</span>
                    </b>
                    <span class="hrst-label" dir="{{ $fa ? 'rtl' : 'ltr' }}">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>

        <div style="display: grid; gap: .75rem; justify-items: center">
            <span class="hrst-verify">
                {!! \NabuXUI\NabuXUI::icon('shield') !!}
                {{ $say('SOC 2 Type II · ISO 27001 · independently verified, 2026', 'SOC 2 نوع دوم · ISO 27001 · تأییدشده به‌صورت مستقل، ۲۰۲۶') }}
            </span>
            <button type="button" class="hrst-replay" x-on:click="replay()">
                {!! \NabuXUI\NabuXUI::icon('play') !!}
                {{ $say('Count them again', 'دوباره بشمار') }}
            </button>
        </div>
    </div>
</section>
