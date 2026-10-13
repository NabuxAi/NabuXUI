{{--
    <x-nx::scroll-scramble title="Decoding every script" :items="['English', 'Español', '日本語', 'فارسی']" height="220vh" />

    A tall section with a pinned stage: the headline decodes in step with the scroll
    (Persian and Arabic scramble with their own letters) while the chips fly out of a
    pile into a row. charset: latin | persian | cuneiform | any string. Reduced motion
    shows the finished state.
--}}
@props(['title' => '', 'items' => [], 'height' => '220vh', 'charset' => null, 'as' => 'h2'])
@php
    $text = trim((string) $title);
    $items = array_values($items);
    $shaped = preg_match('/[\x{0600}-\x{06FF}\x{0700}-\x{074F}\x{0750}-\x{077F}\x{07C0}-\x{07FF}\x{08A0}-\x{08FF}\x{0900}-\x{0DFF}\x{0E00}-\x{0EFF}\x{0F00}-\x{0FFF}\x{1000}-\x{109F}\x{1780}-\x{17FF}\x{1800}-\x{18AF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $text);
@endphp
<section {{ $attributes->class('nx-scroll-scramble')->merge(['style' => "--nx-scroll-height: {$height}"]) }} x-data="nxScrollScramble(@js($text), @js($charset))" wire:ignore.self>
    <div class="nx-scroll-scramble-stage">
        <{{ $as }} class="nx-scroll-scramble-title" dir="auto" @if ($shaped) data-shaped @endif>
            <span class="nx-visually-hidden">{{ $text }}</span>
            <span class="nx-scroll-scramble-sizer" aria-hidden="true">{{ $text }}</span>
            {{-- Alpine rewrites this copy on every scroll frame. --}}
            <span class="nx-scroll-scramble-text" aria-hidden="true" wire:ignore>{{ $text }}</span>
        </{{ $as }}>
        @if ($items)
            <ul class="nx-scroll-scramble-chips" role="list" style="--_n: {{ count($items) }}">
                @foreach ($items as $i => $item)
                    <li class="nx-scroll-scramble-chip" dir="auto" style="--nx-i: {{ $i }}" wire:ignore.self>{{ $item }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
