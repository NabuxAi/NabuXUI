{{--
    Glass sliders doing real work: a font-size control whose value restyles
    the sample text on the server with every release, and a volume row with a
    percent suffix. Drag the thumb — it becomes a magnifier over the ticks.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $fontSize = (int) ($state['fontSize'] ?? 17);
    $volume = (int) ($state['volume'] ?? 40);
@endphp
<style>
    .gsl-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; gap: 1.25rem; min-block-size: 19rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gsl-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(26px) saturate(1.15); }
    .gsl-blobs i { position: absolute; inline-size: 44%; aspect-ratio: 1; border-radius: 50%; opacity: .82; animation: gsl-drift 18s ease-in-out infinite alternate; }
    .gsl-blobs i:nth-child(1) { inset-block-start: -10%; inset-inline-start: -6%; background: var(--nx-violet-500); }
    .gsl-blobs i:nth-child(2) { inset-block-end: -14%; inset-inline-end: -8%; background: var(--nx-cyan-400); animation-delay: -9s; }
    @keyframes gsl-drift { to { translate: calc(8% * var(--nx-motion)) calc(8% * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .gsl-blobs i { animation: none; } }
</style>

<section class="gsl-stage">
    <div class="gsl-blobs" aria-hidden="true"><i></i><i></i></div>
    <x-nx::glass-panel style="display: grid; gap: 1.25rem; inline-size: min(100%, 27rem)">
        <div class="pg-row" style="justify-content: space-between">
            <h3 class="pg-title" style="margin: 0; font-size: var(--nx-text-xl)">{{ $say('Reading size', 'اندازهٔ خواندن') }}</h3>
            <x-nx::badge tone="accent">{{ $fa ? \NabuXUI\NabuXUI::formatNumber($fontSize) : $fontSize }}px</x-nx::badge>
        </div>
        <x-nx::glass-slider :label="$say('Body text size', 'اندازهٔ متن بدنه')" :value="$fontSize"
            wire:model.live="state.fontSize" :min="13" :max="26" :step="1" :ticks="14" suffix="px" />
        <p style="margin: 0; line-height: 1.8; font-size: {{ $fontSize }}px; transition: font-size .2s var(--nx-ease-out)">
            {{ $say('The quick brown fox jumps over the lazy dog — set this line free at whatever size reads best.', 'متن نمونه برای سنجش اندازه — این سطر را در هر اندازه‌ای که راحت‌تر خوانده می‌شود رها کنید.') }}
        </p>
        <x-nx::glass-button size="sm" icon="check" wire:click="save(@js($say('Reading size saved', 'اندازهٔ خواندن ذخیره شد')))">{{ $say('Save size', 'ذخیرهٔ اندازه') }}</x-nx::glass-button>
    </x-nx::glass-panel>
</section>

<section class="gsl-stage" style="min-block-size: 15rem">
    <div class="gsl-blobs" aria-hidden="true"><i></i><i></i></div>
    <x-nx::glass-panel preset="clear" style="display: grid; gap: 1rem; inline-size: min(100%, 22rem)">
        <div class="pg-row" style="justify-content: space-between">
            <h3 class="pg-title" style="margin: 0; font-size: var(--nx-text-lg)">{{ $say('Speaker volume', 'بلندی بلندگو') }}</h3>
            <x-nx::badge>{{ $fa ? \NabuXUI\NabuXUI::formatNumber($volume) : $volume }}{{ $say('%', '٪') }}</x-nx::badge>
        </div>
        <x-nx::glass-slider :label="$say('Speaker volume', 'بلندی بلندگو')" :value="$volume"
            wire:model.live="state.volume" :min="0" :max="100" :step="5" :ticks="11" suffix="{{ $say('%', '٪') }}" />
        <div class="pg-row" style="gap: 1.25rem; color: var(--nx-text-muted)">
            <span style="font-size: var(--nx-text-sm)">{{ $say('Arrow keys nudge by the step; Home and End jump to the rails.', 'کلیدهای فلشی به اندازهٔ گام می‌روند؛ Home و End به دو سر می‌پرند.') }}</span>
        </div>
    </x-nx::glass-panel>
</section>
