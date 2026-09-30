{{--
    Glass panels need something behind them: a drifting blob field carries a
    music player (iridescent, following the light, with a live volume slider
    inside), then the four presets sit side by side as labelled tiles.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .gpn-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; min-block-size: 22rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gpn-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(28px) saturate(1.2); }
    .gpn-blobs i { position: absolute; inline-size: 46%; aspect-ratio: 1; border-radius: 50%; opacity: .85; animation: gpn-drift 18s ease-in-out infinite alternate; }
    .gpn-blobs i:nth-child(1) { inset-block-start: -12%; inset-inline-start: -6%; background: var(--nx-lapis-500); }
    .gpn-blobs i:nth-child(2) { inset-block-end: -18%; inset-inline-start: 22%; background: var(--nx-violet-500); animation-delay: -6s; }
    .gpn-blobs i:nth-child(3) { inset-block-start: 8%; inset-inline-end: -8%; background: var(--nx-cyan-400); animation-delay: -11s; }
    .gpn-blobs i:nth-child(4) { inset-block-end: -6%; inset-inline-end: 12%; inline-size: 28%; background: var(--nx-gold-400); animation-delay: -3s; }
    @keyframes gpn-drift { to { translate: calc(9% * var(--nx-motion)) calc(-7% * var(--nx-motion)); scale: calc(1 + .12 * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .gpn-blobs i { animation: none; } }
</style>

<section class="gpn-stage">
    <div class="gpn-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
    <div style="display: grid; gap: .75rem; inline-size: min(100%, 26rem)">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl); color: var(--nx-text)">{{ $say('A player floating over the artwork', 'پخش‌کننده‌ای شناور روی جلد اثر') }}</h3>
        <x-nx::glass-panel iridescent follow-light style="display: grid; gap: 1rem">
            <div class="pg-row" style="justify-content: space-between">
                <div class="pg-row" style="gap: .75rem">
                    <span aria-hidden="true" style="inline-size: 3rem; aspect-ratio: 1; border-radius: var(--nx-radius-lg); background: linear-gradient(135deg, var(--nx-lapis-500), var(--nx-violet-500))"></span>
                    <div style="display: grid">
                        <strong>{{ $say('Rain in Tehran', 'باران تهران') }}</strong>
                        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('Lo-fi for building design systems', 'لوفای ساخت سیستم طراحی') }}</span>
                    </div>
                </div>
                <x-nx::badge tone="accent" dot>{{ $say('lossless', 'بی‌افت') }}</x-nx:badge>
            </div>
            <x-nx::glass-slider :value="$state['volume'] ?? 40" wire:model.live="state.volume" :label="$say('Volume', 'بلندی صدا')" suffix="٪" :ticks="9" />
            <div class="pg-row" style="justify-content: space-between">
                <x-nx::glass-button icon="play" shimmer wire:click="ping(@js($say('Playing through the office speaker', 'پخش از بلندگوی دفتر')))">{{ $say('Play', 'پخش') }}</x-nx:glass-button>
                <x-nx::glass-button icon="heart" iridescent wire:click="ping(@js($say('Saved to favourites', 'به علاقه‌مندی‌ها رفت')))">{{ $say('Save', 'ذخیره') }}</x-nx:glass-button>
            </div>
        </x-nx::glass-panel>
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted); text-align: center">
            {{ $say('Slide the volume — the panel stays a panel; only the light moves.', 'صدا را بکشید — پنل پنل می‌ماند؛ فقط نور حرکت می‌کند.') }}
        </p>
    </div>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The four bodies', 'چهار جنس شیشه') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('regular (the default), clear, frost and thick — same stage, four readings of the same backdrop.', 'regular (پیش‌فرض)، clear، frost و thick — یک صحنه، چهار خوانش از یک پشتوانه.') }}
    </p>
    <div class="gpn-stage" style="min-block-size: 13rem">
        <div class="gpn-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
        <div class="pg-row" style="gap: 1rem">
            @foreach ([['regular', $say('regular', 'معمولی')], ['clear', $say('clear', 'شفاف')], ['frost', $say('frost', 'یخ‌زده')], ['thick', $say('thick', 'ستبر')]] as [$preset, $name])
                <x-nx::glass-panel :preset="$preset" style="display: grid; gap: .375rem; padding: 1rem 1.25rem; justify-items: center">
                    <strong>{{ $name }}</strong>
                    <code>{{ $preset }}</code>
                </x-nx::glass-panel>
            @endforeach
        </div>
    </div>
</section>
