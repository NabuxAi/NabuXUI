{{--
    The word reveal hero as a real first screen: the display title climbs out
    from under its mask word by word — each word on its own 90ms delay — while
    the rotating word swaps between three every 2.4s (the old word lifts away,
    the fresh one rises in). The subtitle and CTAs land afterwards, so the
    page opens like a teaser; the replay control re-runs the whole entrance.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .hrr-hero {
        --hrr-bg: linear-gradient(180deg, #fdfcf8 0%, #f6f4ec 100%);
        --hrr-ink: #1d1b16; --hrr-muted: #6f6a5c;
        --hrr-accent: #b3541e; --hrr-accent-2: #d98324;
        padding: clamp(3.5rem, 9vw, 7rem) 1.25rem;
        background: var(--hrr-bg); color: var(--hrr-ink);
        display: grid; gap: 1.5rem; justify-items: center; text-align: center;
        overflow: clip; position: relative;
    }
    html[data-theme="dark"] .hrr-hero {
        --hrr-bg: linear-gradient(180deg, #171410 0%, #12100c 100%);
        --hrr-ink: #f4efe4; --hrr-muted: #b0a893;
        --hrr-accent: #f0a35c; --hrr-accent-2: #e2b56a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hrr-hero {
            --hrr-bg: linear-gradient(180deg, #171410 0%, #12100c 100%);
            --hrr-ink: #f4efe4; --hrr-muted: #b0a893;
            --hrr-accent: #f0a35c; --hrr-accent-2: #e2b56a;
        }
    }
    .hrr-hero::before { content: ""; position: absolute; inset: 0; pointer-events: none;
                        background: radial-gradient(60rem 24rem at 50% -8rem,
                            color-mix(in oklab, var(--hrr-accent) 10%, transparent), transparent 70%); }
    .hrr-title { position: relative; margin: 0; max-inline-size: 20ch;
                 display: flex; flex-wrap: wrap; justify-content: center; gap: 0 .45ch;
                 font: 800 clamp(2.6rem, 8.5vw, 5.5rem) / 1.08 var(--nx-font-display);
                 letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .hrr-w { overflow: clip; padding-block-end: .08em; margin-block-end: -.08em; }
    .hrr-wi { display: inline-block; translate: 0 115%; }
    .hrr-hero[data-run] .hrr-wi { animation: hrr-rise .75s cubic-bezier(.2,.7,.2,1) forwards;
                                   animation-delay: calc(var(--i) * 90ms + var(--hrr-base, 0ms)); }
    @keyframes hrr-rise { to { translate: 0 0; } }
    .hrr-flip { display: inline-grid; overflow: clip; padding-block-end: .1em; margin-block-end: -.1em; }
    .hrr-flip > .hrr-w { align-self: center; }
    .hrr-word { grid-area: 1 / 1; white-space: nowrap;
                background: linear-gradient(94deg, var(--hrr-accent), var(--hrr-accent-2));
                background-clip: text; -webkit-background-clip: text; color: transparent;
                transition: translate .55s cubic-bezier(.2,.7,.2,1), opacity .55s; }
    .hrr-word[data-state="in"] { translate: 0 0; opacity: 1; }
    .hrr-word[data-state="out"] { translate: 0 -115%; opacity: 0; }
    .hrr-word[data-state="wait"] { translate: 0 115%; opacity: 0; }
    .hrr-sub { position: relative; margin: 0; max-inline-size: 48ch; font-size: var(--nx-text-lg);
               color: var(--hrr-muted); text-wrap: pretty; opacity: 0; }
    .hrr-ctas { position: relative; display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center;
                margin-block-start: .25rem; opacity: 0; }
    .hrr-hero[data-run] .hrr-sub { animation: hrr-late .7s ease-out forwards; animation-delay: .9s; }
    .hrr-hero[data-run] .hrr-ctas { animation: hrr-late .7s ease-out forwards; animation-delay: 1.15s; }
    @keyframes hrr-late { from { opacity: 0; translate: 0 .9rem; } to { opacity: 1; translate: 0 0; } }
    .hrr-replay { position: relative; display: inline-flex; align-items: center; gap: .4rem;
                  margin-block-start: 1rem; border: none; background: none; cursor: pointer;
                  font: 600 var(--nx-text-xs)/1 inherit; color: var(--hrr-muted);
                  padding: .4rem .75rem; border-radius: 999px;
                  border: 1px solid color-mix(in oklab, var(--hrr-ink) 14%, transparent);
                  transition: color .2s, border-color .2s; }
    .hrr-replay:hover { color: var(--hrr-ink); border-color: color-mix(in oklab, var(--hrr-ink) 30%, transparent); }
    .hrr-replay svg { inline-size: .85rem; aspect-ratio: 1; }
    @media (prefers-reduced-motion: reduce) {
        .hrr-hero[data-run] .hrr-wi, .hrr-hero[data-run] .hrr-sub, .hrr-hero[data-run] .hrr-ctas {
            animation-duration: .01ms; animation-delay: 0ms; }
        .hrr-word { transition-duration: .01ms; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="hrr-hero"
        x-data="{
            i: 0, prev: 2, run: true,
            words: {{ \Illuminate\Support\Js::from($fa ? ['سریع‌تر', 'آرام‌تر', 'با هم'] : ['faster', 'calmer', 'together']) }},
            tick() { this.prev = this.i; this.i = (this.i + 1) % this.words.length },
            replay() {
                this.run = false;
                setTimeout(() => { this.run = true }, 60);
            },
        }"
        x-init="timer = setInterval(() => tick(), 2400)"
        :data-run="run ? '' : null">
        <x-nx::badge tone="warning" dot>{{ $say('Now with auto-rollback', 'اکنون با واگرد خودکار') }}</x-nx::badge>

        <h2 class="hrr-title">
            @php
                $words = $fa
                    ? ['منتشر', 'کن', '—', 'واگرد', 'بی‌ترس.']
                    : ['Ship', '—', 'rollback', 'without', 'fear.'];
            @endphp
            @if ($fa)
                <span class="hrr-flip" style="--i: 0" aria-live="polite">
                    <template x-for="(w, k) in words" :key="k">
                        <span class="hrr-word" :data-state="k === i ? 'in' : k === prev ? 'out' : 'wait'" x-text="w"></span>
                    </template>
                </span>
                @foreach ($words as $k => $w)
                    <span class="hrr-w" style="--i: {{ $k + 1 }}"><span class="hrr-wi">{{ $w }}</span></span>
                @endforeach
            @else
                <span class="hrr-w" style="--i: 0"><span class="hrr-wi">Ship</span></span>
                <span class="hrr-flip" style="--i: 1" aria-live="polite">
                    <template x-for="(w, k) in words" :key="k">
                        <span class="hrr-word" :data-state="k === i ? 'in' : k === prev ? 'out' : 'wait'" x-text="w"></span>
                    </template>
                </span>
                @foreach (array_slice($words, 1) as $k => $w)
                    <span class="hrr-w" style="--i: {{ $k + 2 }}"><span class="hrr-wi">{{ $w }}</span></span>
                @endforeach
            @endif
        </h2>

        <p class="hrr-sub">
            {{ $say('Asterly watches every canary so you don’t have to — p95 checks on each step, automatic rollback and a paper trail your auditors will love.', 'استرلی هر قنری را زیر نظر دارد تا تو نداشته باشی — بررسی p95 روی هر پله، واگرد خودکار و سابقه‌ای که حسابرس‌هاشت دوستش دارند.') }}
        </p>

        <div class="hrr-ctas">
            <x-nx::button variant="primary" shape="pill" size="lg" icon-end="arrow-right">
                {{ $say('Start releasing', 'شروع انتشار') }}
            </x-nx::button>
            <x-nx::button variant="ghost" shape="pill" size="lg" icon="play">
                {{ $say('See a live rollback', 'دیدن یک واگرد زنده') }}
            </x-nx::button>
        </div>

        <button type="button" class="hrr-replay" x-on:click="replay()">
            {!! \NabuXUI\NabuXUI::icon('play') !!}
            {{ $say('Replay the reveal', 'بازپخش ظهور') }}
        </button>
    </div>
</section>
