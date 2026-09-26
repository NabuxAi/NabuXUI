{{-- Every press sends a ripple through the live content inside. <x-nx::liquid-ripple :strength="18">…</x-nx::liquid-ripple> --}}
@props(['strength' => 18, 'duration' => 1100])
<div {{ $attributes->class('nx-liquid-ripple') }} x-data x-nx-liquid-ripple="{ strength: @js((float) $strength), duration: @js((int) $duration) }">{{ $slot }}</div>
