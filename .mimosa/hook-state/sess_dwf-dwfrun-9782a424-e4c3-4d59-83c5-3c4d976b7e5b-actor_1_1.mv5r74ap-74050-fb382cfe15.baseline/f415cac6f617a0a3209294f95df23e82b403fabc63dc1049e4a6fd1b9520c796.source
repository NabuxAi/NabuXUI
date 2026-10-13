{{--
    The spotlight hero as a real first screen: an ink-dark stage whose halo
    of light travels with the pointer over a calm gradient mesh (three
    drifting blobs), badge over the display title, two CTAs and the trust
    row beneath the buttons. The light is direct manipulation — it stays
    with the hand even under reduced motion; only the mesh freezes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
@endphp
<style>
    .hrsp-stage {
        --hrsp-mx: 50%; --hrsp-my: 26%;
        --hrsp-ink: #0b1020; --hrsp-ink-2: #10173a;
        --hrsp-text: #eef1ff; --hrsp-muted: #aab3e8;
        --hrsp-glow: #8ea2ff; --hrsp-accent: #6d7cff;
        position: relative; overflow: clip; isolation: isolate;
        padding: clamp(3.5rem, 9vw, 7rem) 1.25rem;
        background: radial-gradient(120% 90% at 50% 0%, var(--hrsp-ink-2), var(--hrsp-ink) 60%);
        color: var(--hrsp-text); text-align: center;
        display: grid; gap: 1.25rem; justify-items: center;
    }
    html[data-theme="dark"] .hrsp-stage { --hrsp-ink: #070b1c; --hrsp-ink-2: #0d1330; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hrsp-stage { --hrsp-ink: #070b1c; --hrsp-ink-2: #0d1330; }
    }
    .hrsp-blob { position: absolute; z-index: -1; border-radius: 50%; filter: blur(90px); opacity: .5;
                 background: radial-gradient(circle, var(--hrsp-b, #4f46e5), transparent 70%);
                 animation: hrsp-drift var(--hrsp-t, 30s) ease-in-out infinite alternate; }
    .hrsp-blob:nth-child(1) { inline-size: 34rem; aspect-ratio: 1; inset-block-start: -12rem; inset-inline-start: -8rem; --hrsp-b: #4f46e5; --hrsp-t: 26s; }
    .hrsp-blob:nth-child(2) { inline-size: 28rem; aspect-ratio: 1.4; inset-block-end: -10rem; inset-inline-end: -6rem; --hrsp-b: #7c3aed; --hrsp-t: 32s; animation-direction: alternate-reverse; }
    .hrsp-blob:nth-child(3) { inline-size: 20rem; aspect-ratio: 1; inset-block-start: 20%; inset-inline-start: 58%; --hrsp-b: #0ea5e9; --hrsp-t: 38s; opacity: .32; }
    @keyframes hrsp-drift {
        from { translate: 0 0; scale: 1; }
        to   { translate: var(--hrsp-dx, 6rem) var(--hrsp-dy, -3rem); scale: 1.18; }
    }
    .hrsp-light { position: absolute; inset: 0; z-index: -1; pointer-events: none;
                  background:
                    radial-gradient(34rem circle at var(--hrsp-mx) var(--hrsp-my),
                        color-mix(in oklab, var(--hrsp-glow) 24%, transparent),
                        color-mix(in oklab, var(--hrsp-glow) 7%, transparent) 42%,
                        transparent 70%),
                    radial-gradient(7rem circle at var(--hrsp-mx) var(--hrsp-my),
                        color-mix(in oklab, #ffffff 10%, transparent), transparent 100%); }
    .hrsp-copy { display: grid; gap: 1rem; justify-items: center; max-inline-size: 46rem; }
    .hrsp-title { margin: 0; font: 800 clamp(2.4rem, 7vw, 4.75rem) / 1.06 var(--nx-font-display);
                  letter-spacing: var(--nx-tracking-tight);
                  text-wrap: balance; text-shadow: 0 0 3rem color-mix(in oklab, var(--hrsp-glow) 45%, transparent); }
    .hrsp-title em { font-style: normal;
                     background: linear-gradient(92deg, var(--hrsp-glow), #c084fc 55%, var(--hrsp-glow));
                     background-clip: text; -webkit-background-clip: text; color: transparent; }
    .hrsp-sub { margin: 0; max-inline-size: 42ch; font-size: var(--nx-text-lg);
                color: var(--hrsp-muted); text-wrap: pretty; }
    .hrsp-ctas { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
    .hrsp-trust { display: flex; flex-wrap: wrap; gap: .375rem 1.25rem; justify-content: center;
                  margin: .25rem 0 0; font-size: var(--nx-text-xs); color: var(--hrsp-muted); }
    .hrsp-trust span { display: inline-flex; align-items: center; gap: .375rem; }
    .hrsp-trust svg { inline-size: .875rem; aspect-ratio: 1; color: color-mix(in oklab, var(--hrsp-glow) 80%, white); }
    @media (prefers-reduced-motion: reduce) {
        .hrsp-blob { animation: none; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="hrsp-stage"
        x-data="{
            mx: 50, my: 26, joined: false,
            track(e) {
                const r = this.$refs.sky.getBoundingClientRect();
                this.mx = Math.min(100, Math.max(0, (e.clientX - r.left) / r.width * 100));
                this.my = Math.min(100, Math.max(0, (e.clientY - r.top) / r.height * 100));
            },
        }"
        x-ref="sky"
        x-on:pointermove="track($event)"
        :style="`--hrsp-mx:${mx}%; --hrsp-my:${my}%`">
        <i class="hrsp-blob" aria-hidden="true"></i>
        <i class="hrsp-blob" aria-hidden="true"></i>
        <i class="hrsp-blob" aria-hidden="true"></i>
        <div class="hrsp-light" aria-hidden="true"></div>

        <div class="hrsp-copy">
            <x-nx::badge tone="info" dot pulse>{{ $say('Asterly 3.0 — generally available', 'استرلی ۳٫۰ — اکنون به‌صورت عمومی') }}</x-nx::badge>
            <h1 class="hrsp-title">
                {!! $say('Ship with the <em>lights on</em>', 'با <em>روشنایی</em> منتشر کن') !!}
            </h1>
            <p class="hrsp-sub">
                {{ $say('Asterly puts every deploy in Frankfurt, Dublin and Singapore under one beam — traces, logs and releases on a single calm surface.', 'استرلی هر استقرار در فرانکفورت، دوبلین و سنگاپور را زیر یک نور می‌گیرد — تریس‌ها، لاگ‌ها و انتشارها روی یک سطحِ آرام.') }}
            </p>
            <div class="hrsp-ctas">
                <x-nx::button variant="primary" shape="pill" size="lg" icon-end="arrow-right" x-on:click="joined = !joined">
                    <span x-text="joined ? {{ \Illuminate\Support\Js::from($say('Welcome aboard', 'خوش آمدی'))->toHtml() }} : {{ \Illuminate\Support\Js::from($say('Start free', 'شروع رایگان'))->toHtml() }}">{{ $say('Start free', 'شروع رایگان') }}</span>
                </x-nx::button>
                <x-nx::button variant="ghost" shape="pill" size="lg" icon="play" style="color: var(--hrsp-text)">
                    {{ $say('Watch the 4-min tour', 'تور چهاردقیقه‌ای') }}
                </x-nx::button>
            </div>
            <p class="hrsp-trust">
                <span>{!! \NabuXUI\NabuXUI::icon('shield') !!}{{ $say('SOC 2 Type II', 'SOC 2 نوع دوم') }}</span>
                <span>{!! \NabuXUI\NabuXUI::icon('check-circle') !!}{{ $say($num('99.99%').' uptime SLA', 'آپ‌تایم '.$num('99.99%').' با توافق SLA') }}</span>
                <span>{!! \NabuXUI\NabuXUI::icon('users') !!}{{ $say($num('4,200').'+ teams', 'بیش از ۴٬۲۰۰ تیم') }}</span>
            </p>
        </div>
    </div>
</section>
