{{--
    A glass surface: tint, frost, a rim that catches the light, soft depth.
    <x-nx::glass-panel preset="clear" iridescent follow-light>…</x-nx::glass-panel>
    preset: regular (default) | clear | frost | thick. refract: bend the backdrop at the rim (Chromium).
--}}
@props(['preset' => 'regular', 'refract' => true, 'iridescent' => false, 'followLight' => false, 'as' => 'div'])
<{{ $as }} {{ $attributes->class('nx-glass nx-glass-panel')->merge([
    'data-preset' => $preset === 'regular' ? null : $preset,
    'data-iridescent' => $iridescent ? '' : null,
]) }} @if ($refract || $followLight) x-data @endif @if ($refract && $followLight) x-nx-glass.follow @elseif ($refract) x-nx-glass @elseif ($followLight) x-nx-glass.follow @endif>{{ $slot }}</{{ $as }}>
