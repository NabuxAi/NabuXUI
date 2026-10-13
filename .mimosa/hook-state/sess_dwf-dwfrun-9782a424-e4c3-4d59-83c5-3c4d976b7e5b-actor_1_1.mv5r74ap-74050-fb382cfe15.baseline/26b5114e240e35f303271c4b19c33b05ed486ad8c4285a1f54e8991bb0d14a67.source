{{--
    A lens over live content: drag it, throw it, or move it with the arrow keys.
    <x-nx::reading-glass :x="0.6" :y="0.3">…any content…</x-nx::reading-glass>
    shape: circle (default) | pill | rounded. size: the short side in px.
--}}
@props(['size' => 144, 'shape' => 'circle', 'x' => 0.5, 'y' => 0.5, 'label' => null])
<div {{ $attributes->class('nx-reading-glass') }} x-data x-nx-reading-glass="{ x: @js((float) $x), y: @js((float) $y) }">
    <div class="nx-reading-glass-content">{{ $slot }}</div>
    <button type="button" class="nx-lens nx-reading-glass-lens" style="--_size: {{ (int) $size }}px" @if ($shape !== 'circle') data-shape="{{ $shape }}" @endif
        aria-label="{{ $label ?? __('nabuxui::ui.magnifier') }}"></button>
</div>
