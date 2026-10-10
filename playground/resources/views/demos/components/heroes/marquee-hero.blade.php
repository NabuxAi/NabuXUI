{{--
    The logo marquee hero as a real first screen: display title, subtitle and
    two CTAs, then the "trusted by" strip — an infinite, pure-CSS marquee of
    simple dual SVG logos that pauses on hover and dissolves into a gradient
    mask at both ends. The belt itself stays LTR on purpose: the marks are
    geometric and the wordmarks Latin, so the loop's seam stays seamless in
    both page directions.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
@endphp
<style>
    .hrm-hero {
        --hrm-bg: radial-gradient(120% 100% at 50% 0%, #ffffff, #f3f4fd 70%);
        --hrm-ink: #12152b; --hrm-muted: #575d85;
        --hrm-accent: #5b5bd6; --hrm-mark: #6a7096;
        padding: clamp(3.5rem, 8vw, 6.5rem) 1.25rem clamp(2.5rem, 5vw, 4rem);
        background: var(--hrm-bg); color: var(--hrm-ink);
        display: grid; gap: 1.25rem; justify-items: center; text-align: center;
        overflow: clip;
    }
    html[data-theme="dark"] .hrm-hero {
        --hrm-bg: radial-gradient(120% 100% at 50% 0%, #12152b, #0d1026 70%);
        --hrm-ink: #eef1ff; --hrm-muted: #aab3e8;
        --hrm-accent: #8a94ff; --hrm-mark: #9aa2cc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hrm-hero {
            --hrm-bg: radial-gradient(120% 100% at 50% 0%, #12152b, #0d1026 70%);
            --hrm-ink: #eef1ff; --hrm-muted: #aab3e8;
            --hrm-accent: #8a94ff; --hrm-mark: #9aa2cc;
        }
    }
    .hrm-title { margin: 0; max-inline-size: 18ch; font: 800 clamp(2.4rem, 7vw, 4.75rem) / 1.06 var(--nx-font-display);
                 letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .hrm-sub { margin: 0; max-inline-size: 46ch; font-size: var(--nx-text-lg); color: var(--hrm-muted); text-wrap: pretty; }
    .hrm-ctas { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; margin-block-start: .5rem; }
    .hrm-note { margin: 0; font-size: var(--nx-text-xs); color: var(--hrm-muted); }
    .hrm-label { margin: 2.75rem 0 0; font: 600 .72rem/1 var(--nx-font-mono);
                 letter-spacing: .18em; text-transform: uppercase; color: var(--hrm-muted); }
    .hrm-belt { inline-size: min(100%, 64rem); overflow: clip; direction: ltr;
                mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent);
                -webkit-mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent); }
    .hrm-track { display: flex; inline-size: max-content; animation: hrm-scroll 32s linear infinite; }
    .hrm-belt:hover .hrm-track, .hrm-belt:focus-within .hrm-track { animation-play-state: paused; }
    .hrm-group { display: flex; align-items: center; gap: clamp(2rem, 5vw, 3.5rem); padding-inline-end: clamp(2rem, 5vw, 3.5rem); }
    @keyframes hrm-scroll { to { translate: -50% 0; } }
    .hrm-logo { display: inline-flex; align-items: center; gap: .6rem; flex: none;
                color: var(--hrm-mark); font: 600 clamp(.95rem, 2.6vw, 1.1rem)/1 system-ui, sans-serif;
                letter-spacing: -.01em; transition: color .2s ease; }
    .hrm-logo:hover, .hrm-logo:focus-visible { color: var(--hrm-ink); }
    .hrm-logo svg { inline-size: clamp(1.25rem, 3.4vw, 1.6rem); aspect-ratio: 1; }
    .hrm-logo b { font-weight: 650; }
    @media (prefers-reduced-motion: reduce) {
        .hrm-track { animation: none; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="hrm-hero"
        x-data="{ joined: false }">
        <x-nx::badge tone="neutral" dot>{{ $say('Series A — led by Northline Ventures', 'سری A — با رهبری نورث‌لاین ونچرز') }}</x-nx::badge>
        <h2 class="hrm-title">
            {{ $say('Where fast teams ship', 'جایی که تیم‌های سریع منتشر می‌کنند') }}
        </h2>
        <p class="hrm-sub">
            {{ $say('Asterly is the rollout layer engineering teams trust with their busiest hours — from seed-stage in Toronto to global fleets in Singapore.', 'استرلی لایهٔ انتشارِی است که تیم‌های مهندسی به آن ساعت‌های شلوغشان را می‌سپارند — از استارت‌آپ تورنتو تا ناوگان جهانی سنگاپور.') }}
        </p>
        <div class="hrm-ctas">
            <x-nx::button variant="primary" shape="pill" size="lg" icon-end="arrow-right" x-on:click="joined = !joined">
                <span x-text="joined ? {{ \Illuminate\Support\Js::from($say('Seat reserved ✓', 'صندلی رزرو شد ✓'))->toHtml() }} : {{ \Illuminate\Support\Js::from($say('Start free', 'شروع رایگان'))->toHtml() }}">{{ $say('Start free', 'شروع رایگان') }}</span>
            </x-nx::button>
            <x-nx::button variant="ghost" shape="pill" size="lg" icon="chart">
                {{ $say('Read the benchmark', 'خواندن بنچمارک') }}
            </x-nx::button>
        </div>
        <p class="hrm-note">{{ $say('No credit card · cancel with one click', 'بدون کارت بانکی · لغو با یک کلیک') }}</p>

        <p class="hrm-label">{{ $say('Trusted by shipping teams at', 'مورد اعتمادِ تیم‌های ارسال در') }}</p>
        <div class="hrm-belt">
            <div class="hrm-track">
                @foreach ([false, true] as $mirror)
                    <div class="hrm-group" @if ($mirror) aria-hidden="true" @endif>
                        @foreach ([
                            ['Nordhaus', 'M4 20 12 5l8 15H4Z'],
                            ['Kitework', 'M12 2l9 5v10l-9 5-9-5V7l9-5Z'],
                            ['Fjordline', 'M2 15c3-6 5 6 8 0s5 6 8 0'],
                            ['Bluepeak', 'M9 12a5 5 0 1 0 10 0 5 5 0 0 0-10 0Zm-4 0a5 5 0 1 0 10 0 5 5 0 0 0-10 0Z'],
                            ['Halden', 'M12 2l7 10-7 10-7-10 7-10Z'],
                            ['Montra', 'M20 12a8 8 0 1 1-6-7.7'],
                            ['Vektor', 'M5 19 12 6l7 13M8.5 13h7'],
                            ['Auralis', 'M12 3v18M3 12h18M6 6l12 12M18 6 6 18'],
                        ] as [$name, $path])
                            <span class="hrm-logo">
                                <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="{{ $path }}" />
                                </svg>
                                <b>{{ $name }}</b>
                            </span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
