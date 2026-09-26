@props(['size' => 'md', 'label' => null])
@php $sizes = ['sm' => '2.25rem', 'md' => '3.5rem', 'lg' => '5rem']; @endphp
<span {{ $attributes->class('nx-orbit')->merge(['role' => 'status', 'style' => '--nx-loader-size: '.($sizes[$size] ?? $sizes['md'])]) }}>
    <svg viewBox="0 0 64 64" aria-hidden="true">
        @foreach ([['0deg', 'var(--nx-lapis-500)', '1.6s'], ['60deg', 'var(--nx-cyan-400)', '2.1s'], ['120deg', 'var(--nx-violet-500)', '2.6s']] as [$r, $c, $d])
            <g class="nx-orbit-ring" style="--r: {{ $r }}; --c: {{ $c }}; --d: {{ $d }}">
                <ellipse class="nx-orbit-path" cx="32" cy="32" rx="26" ry="10"/>
                <ellipse class="nx-orbit-comet" cx="32" cy="32" rx="26" ry="10" pathLength="100"/>
            </g>
        @endforeach
        <circle class="nx-orbit-core" cx="32" cy="32" r="4.5"/>
    </svg>
    <span class="nx-visually-hidden">{{ $label ?? __('nabuxui::ui.loading') }}</span>
</span>
