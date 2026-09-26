{{-- Wedges pressed into clay one after another: the Nabu scribe at work. --}}
@props(['size' => 'md', 'label' => null])
@php $sizes = ['sm' => '3rem', 'md' => '4.5rem', 'lg' => '6.5rem']; @endphp
@php $wedge = 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z'; @endphp
<span {{ $attributes->class('nx-cuneiform')->merge(['role' => 'status', 'style' => '--nx-loader-size: '.($sizes[$size] ?? $sizes['md'])]) }}>
    <svg viewBox="0 0 64 24" aria-hidden="true">
        <g transform="translate(2 7)"><path class="nx-wedge" d="{{ $wedge }}" style="--i: 0"/></g>
        <g transform="translate(24 1) rotate(90 5 5)"><path class="nx-wedge" d="{{ $wedge }}" style="--i: 1"/></g>
        <g transform="translate(34 7)"><path class="nx-wedge" d="{{ $wedge }}" style="--i: 2"/></g>
        <g transform="translate(52 5)"><path class="nx-wedge" d="M10 0L0 7L10 14L6.5 7Z" style="--i: 3"/></g>
    </svg>
    <span class="nx-visually-hidden">{{ $label ?? __('nabuxui::ui.loading') }}</span>
</span>
