{{--
    The split hero as a real first screen: copy, sub and two CTAs on the
    start side; the Asterly release console as a tilted browser mockup
    wrapped in a soft halo on the other. Capability chips float over the
    mockup and ride the same pointer tilt at 1.6× — the product hands you
    its angle before you ever scroll.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
@endphp
<style>
    .hrsl-hero {
        --hrsl-rx: 0deg; --hrsl-ry: 0deg; --hrsl-k: 1;
        --hrsl-bg: linear-gradient(165deg, #f7f8ff 0%, #eef0fb 55%, #f2eeff 100%);
        --hrsl-ink: #12152b; --hrsl-muted: #575d85;
        --hrsl-panel: #ffffff; --hrsl-line: #e3e6f5;
        --hrsl-accent: #5b5bd6; --hrsl-ok: #178a50;
        padding: clamp(3rem, 7vw, 5.5rem) clamp(1.25rem, 4vw, 3rem);
        background: var(--hrsl-bg); color: var(--hrsl-ink);
        display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
        gap: clamp(2rem, 5vw, 4.5rem); align-items: center;
    }
    html[data-theme="dark"] .hrsl-hero {
        --hrsl-bg: linear-gradient(165deg, #12152b 0%, #141938 55%, #191238 100%);
        --hrsl-ink: #eef1ff; --hrsl-muted: #aab3e8;
        --hrsl-panel: #181c3a; --hrsl-line: #2b3160;
        --hrsl-accent: #8a94ff; --hrsl-ok: #3ecf8e;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hrsl-hero {
            --hrsl-bg: linear-gradient(165deg, #12152b 0%, #141938 55%, #191238 100%);
            --hrsl-ink: #eef1ff; --hrsl-muted: #aab3e8;
            --hrsl-panel: #181c3a; --hrsl-line: #2b3160;
            --hrsl-accent: #8a94ff; --hrsl-ok: #3ecf8e;
        }
    }
    .hrsl-copy { display: grid; gap: 1.25rem; justify-items: start; }
    .hrsl-title { margin: 0; font: 800 clamp(2.2rem, 5.2vw, 4rem) / 1.08 var(--nx-font-display);
                  letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .hrsl-sub { margin: 0; max-inline-size: 44ch; font-size: var(--nx-text-lg);
                color: var(--hrsl-muted); text-wrap: pretty; }
    .hrsl-ctas { display: flex; flex-wrap: wrap; gap: .75rem; }
    .hrsl-note { margin: 0; font-size: var(--nx-text-xs); color: var(--hrsl-muted); }

    .hrsl-stage { position: relative; perspective: 1200px; perspective-origin: 50% 45%;
                  display: grid; justify-items: center; }
    .hrsl-halo { position: absolute; inset: -12% -18%; z-index: 0; border-radius: 50%; pointer-events: none;
                 background: radial-gradient(closest-side,
                     color-mix(in oklab, var(--hrsl-accent) 34%, transparent), transparent 68%);
                 filter: blur(38px); }
    .hrsl-mock { position: relative; z-index: 1; inline-size: min(100%, 30rem);
                 border-radius: 1rem; overflow: clip;
                 background: var(--hrsl-panel); border: 1px solid var(--hrsl-line);
                 box-shadow: 0 1.25rem 3rem color-mix(in oklab, #0b1020 26%, transparent);
                 transform: rotateY(calc(var(--hrsl-ry) * var(--hrsl-k) - 9deg)) rotateX(calc(var(--hrsl-rx) * var(--hrsl-k)));
                 transition: transform .3s ease-out; transform-style: preserve-3d; }
    html[dir="rtl"] .hrsl-mock { transform: rotateY(calc(var(--hrsl-ry) * var(--hrsl-k) * -1 + 9deg)) rotateX(calc(var(--hrsl-rx) * var(--hrsl-k))); }
    .hrsl-bar { display: flex; align-items: center; gap: .4rem; padding: .6rem .8rem;
                border-block-end: 1px solid var(--hrsl-line); background: color-mix(in oklab, var(--hrsl-panel) 82%, var(--hrsl-accent) 4%); }
    .hrsl-bar i { inline-size: .6rem; aspect-ratio: 1; border-radius: 50%; background: var(--hrsl-line); }
    .hrsl-bar i:nth-child(1) { background: #ff6b6b; } .hrsl-bar i:nth-child(2) { background: #ffd166; } .hrsl-bar i:nth-child(3) { background: #06d6a0; }
    .hrsl-bar b { margin-inline-start: .75rem; padding: .2rem .75rem; border-radius: 999px; font: 500 .72rem/1 var(--nx-font-mono);
                  background: color-mix(in oklab, var(--hrsl-accent) 10%, transparent); color: var(--hrsl-muted);
                  max-inline-size: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .hrsl-body { display: grid; grid-template-columns: auto 1fr; }
    .hrsl-nav { display: grid; gap: .5rem; align-content: start; padding: .8rem .6rem;
                border-inline-end: 1px solid var(--hrsl-line); }
    .hrsl-nav i { inline-size: 4.5rem; block-size: .55rem; border-radius: 999px; background: var(--hrsl-line); }
    .hrsl-nav i:first-child { background: color-mix(in oklab, var(--hrsl-accent) 55%, transparent); }
    .hrsl-main { display: grid; gap: .55rem; padding: .8rem; min-inline-size: 0; }
    .hrsl-row { display: flex; align-items: center; gap: .5rem; padding: .5rem .6rem; border-radius: .6rem;
                border: 1px solid var(--hrsl-line); font: 500 .78rem/1 var(--nx-font-mono); color: var(--hrsl-muted); }
    .hrsl-row b { color: var(--hrsl-ink); font-weight: 600; }
    .hrsl-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 50%; flex: none; }
    .hrsl-dot.ok { background: var(--hrsl-ok); box-shadow: 0 0 0 3px color-mix(in oklab, var(--hrsl-ok) 22%, transparent); }
    .hrsl-dot.run { background: var(--hrsl-accent); animation: hrsl-pulse 1.6s ease-in-out infinite; }
    .hrsl-row small { margin-inline-start: auto; font: 600 .62rem/1 var(--nx-font-mono);
                      padding: .18rem .5rem; border-radius: 999px; }
    .hrsl-row small[data-ok] { color: var(--hrsl-ok); background: color-mix(in oklab, var(--hrsl-ok) 14%, transparent); }
    .hrsl-row small[data-run] { color: var(--hrsl-accent); background: color-mix(in oklab, var(--hrsl-accent) 14%, transparent); }
    .hrsl-bars { display: flex; align-items: end; gap: .3rem; block-size: 2.6rem;
                 padding: .5rem .6rem 0; border-block-start: 1px solid var(--hrsl-line); }
    .hrsl-bars i { flex: 1; border-radius: .2rem .2rem 0 0;
                   background: linear-gradient(to top, color-mix(in oklab, var(--hrsl-accent) 32%, transparent), color-mix(in oklab, var(--hrsl-accent) 72%, transparent));
                   animation: hrsl-grow 1.1s cubic-bezier(.2,.7,.2,1) both; animation-delay: calc(var(--i) * 90ms); }
    @keyframes hrsl-grow { from { block-size: 12%; } }
    @keyframes hrsl-pulse { 50% { box-shadow: 0 0 0 6px transparent; } }
    .hrsl-chip { position: absolute; z-index: 2; display: inline-flex; align-items: center; gap: .45rem;
                 padding: .55rem .85rem; border-radius: 999px; white-space: nowrap;
                 background: var(--hrsl-panel); border: 1px solid var(--hrsl-line); color: var(--hrsl-ink);
                 font-size: var(--nx-text-xs); font-weight: 600;
                 box-shadow: 0 .6rem 1.4rem color-mix(in oklab, #0b1020 18%, transparent);
                 translate: calc(var(--hrsl-ry) * 1.6 * var(--hrsl-k)) calc(var(--hrsl-rx) * -1.6 * var(--hrsl-k));
                 animation: hrsl-float 6s ease-in-out infinite; animation-delay: var(--d, 0s); }
    .hrsl-chip svg { inline-size: .9rem; aspect-ratio: 1; color: var(--hrsl-accent); }
    .hrsl-chip:nth-of-type(1) { inset-block-start: -6%; inset-inline-end: 4%; --d: 0s; }
    .hrsl-chip:nth-of-type(2) { inset-block-start: 38%; inset-inline-start: -7%; --d: 1.4s; }
    .hrsl-chip:nth-of-type(3) { inset-block-end: -7%; inset-inline-end: 12%; --d: 2.6s; }
    @keyframes hrsl-float { 50% { translate: calc(var(--hrsl-ry) * 1.6 * var(--hrsl-k)) calc(var(--hrsl-rx) * -1.6 * var(--hrsl-k) - .45rem); } }
    @media (max-width: 56.25rem) {
        .hrsl-hero { grid-template-columns: 1fr; }
        .hrsl-copy { justify-items: start; }
        .hrsl-stage { margin-block-start: 1.5rem; --hrsl-k: .5; }
        .hrsl-chip:nth-of-type(2) { inset-inline-start: 0; }
        .hrsl-chip:nth-of-type(1), .hrsl-chip:nth-of-type(3) { inset-inline-end: 0; }
        .hrsl-body { grid-template-columns: 1fr; }
        .hrsl-nav { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .hrsl-mock, .hrsl-chip { transition: none; animation: none; }
        .hrsl-bars i { animation-duration: .01ms; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="hrsl-hero"
        x-data="{
            rx: 0, ry: 0, building: false,
            track(e) {
                const r = this.$el.getBoundingClientRect();
                this.rx = Math.max(-1, Math.min(1, (e.clientY - r.top) / r.height - .5)) * -8;
                this.ry = Math.max(-1, Math.min(1, (e.clientX - r.left) / r.width - .5)) * 8;
            },
        }"
        x-on:pointermove="track($event)"
        x-on:pointerleave="rx = 0; ry = 0"
        :style="`--hrsl-rx:${rx}deg; --hrsl-ry:${ry}deg`">
        <div class="hrsl-copy">
            <x-nx::badge tone="neutral" dot>{{ $say('For platform teams', 'برای تیم‌های پلتفرم') }}</x-nx::badge>
            <h2 class="hrsl-title">
                {{ $say('Your console for every rollout', 'کنسولِ تو برای هر انتشار') }}
            </h2>
            <p class="hrsl-sub">
                {{ $say('Asterly sits between your pipeline and your users — progressive delivery, live traces and one-click rollback on a single plane.', 'استرلی بین پایپ‌لاین و کاربرانت می‌نشیند — انتشار تدریجی، تریس زنده و واگردِ تک‌کلیکی روی یک صفحه.') }}
            </p>
            <div class="hrsl-ctas">
                <x-nx::button variant="primary" shape="pill" size="lg" icon-end="arrow-right" x-on:click="building = !building">
                    <span x-text="building ? {{ \Illuminate\Support\Js::from($say('Sandbox ready ✓', 'سندباکس آماده است ✓'))->toHtml() }} : {{ \Illuminate\Support\Js::from($say('Start building', 'شروع ساخت'))->toHtml() }}">{{ $say('Start building', 'شروع ساخت') }}</span>
                </x-nx::button>
                <x-nx::button variant="secondary" shape="pill" size="lg" icon="message">
                    {{ $say('Talk to an engineer', 'گفت‌وگو با یک مهندس') }}
                </x-nx::button>
            </div>
            <p class="hrsl-note">{{ $say('Free for 30 days · no credit card · deploys from your own GitHub org', '۳۰ روز رایگان · بدون کارت بانکی · استقرار از گیتهاب اورگِ خودت') }}</p>
        </div>

        <div class="hrsl-stage" aria-hidden="true">
            <i class="hrsl-halo"></i>
            <div class="hrsl-mock">
                <span class="hrsl-bar"><i></i><i></i><i></i><b>app.asterly.io/rollouts</b></span>
                <div class="hrsl-body">
                    <div class="hrsl-nav"><i></i><i></i><i></i><i></i><i></i></div>
                    <div class="hrsl-main">
                        @foreach ([
                            ['ok', 'api', 'v2.14.0', $say('Healthy', 'سالم'), '100%', 'data-ok'],
                            ['run', 'web', 'v2.13.9', $say('Canary 25%', 'قنری ۲۵٪'), '72%', 'data-run'],
                            ['ok', 'worker', 'v2.14.0', $say('Healthy', 'سالم'), '84%', 'data-ok'],
                        ] as [$dot, $svc, $ver, $state, $w, $data])
                            <div class="hrsl-row">
                                <i class="hrsl-dot {{ $dot }}"></i>
                                <b>{{ $svc }}</b> · {{ $ver }}
                                <small {{ $data }}>{{ $state }}</small>
                            </div>
                        @endforeach
                        <div class="hrsl-bars">
                            @foreach (['38%', '62%', '47%', '78%', '56%', '88%', '70%', '94%', '64%', '82%'] as $i => $h)
                                <i style="block-size: {{ $h }}; --i: {{ $i }}"></i>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <span class="hrsl-chip">{!! \NabuXUI\NabuXUI::icon('zap') !!}{{ $say('Traces in 12ms', 'تریس در '.$num('12').'ms') }}</span>
            <span class="hrsl-chip">{!! \NabuXUI\NabuXUI::icon('check-circle') !!}{{ $say('Zero-downtime rollouts', 'انتشار بدون داون‌تایم') }}</span>
            <span class="hrsl-chip">{!! \NabuXUI\NabuXUI::icon('layers') !!}{{ $say('Preview branches per PR', 'شاخهٔ پیش‌نمایش برای هر PR') }}</span>
        </div>
    </div>
</section>
