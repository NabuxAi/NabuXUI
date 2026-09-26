{{--
    A grid whose columns follow the scroll on springs of their own: they trail, stretch and settle.
    With reduced motion it stays still.

    <x-nx::elastic-grid :columns="3" :items="[
        ['cover' => 'linear-gradient(…)', 'title' => 'Tokyo', 'caption' => '東京'],
        ['image' => '/img/lisboa.jpg', 'alt' => 'Lisbon at dusk', 'href' => '/stories/lisboa'],
        'note' => [],
    ]">
        <x-slot:note>…any content for the cell keyed "note"…</x-slot:note>
    </x-nx::elastic-grid>

    Item keys: cover (a CSS background), image, alt, title, caption, ratio ("4 / 5"), href. An item may also be any
    Htmlable, or a slot named after its key. Items are dealt into the columns in order.
--}}
@props(['items' => [], 'columns' => 3, 'lag' => 90, 'parallax' => 40])
@php
    $slots = $__laravel_slots ?? [];
    $slotFor = fn ($key) => $slots[$key] ?? $slots[\Illuminate\Support\Str::camel((string) $key)] ?? null;
    $cols = max(1, (int) $columns);
    $groups = array_fill(0, $cols, []);
    $n = 0;
    foreach ($items as $key => $item) {
        $groups[$n % $cols][] = ['key' => $key, 'item' => $item];
        $n++;
    }
@endphp
<div {{ $attributes->class('nx-elastic')->merge(['style' => "--nx-columns: {$cols}"]) }} x-data="nxElasticGrid(@js(['max' => (int) $lag, 'parallax' => (int) $parallax]))">
    @foreach ($groups as $group)
        <div class="nx-elastic-col">
            @foreach ($group as $entry)
                @php
                    $item = $entry['item'];
                    $content = $slotFor($entry['key']);
                @endphp
                @if ($content)
                    <div class="nx-elastic-cell">{{ $content }}</div>
                @elseif ($item instanceof \Illuminate\Contracts\Support\Htmlable)
                    <div class="nx-elastic-cell">{{ $item }}</div>
                @elseif (is_array($item))
                    @php
                        $style = trim((! empty($item['cover']) ? "background: {$item['cover']};" : '').(! empty($item['ratio']) ? " --nx-ratio: {$item['ratio']};" : ''));
                        $wrap = ! empty($item['href']) ? 'a' : 'div';
                    @endphp
                    <figure class="nx-elastic-cell">
                        <{{ $wrap }} style="display: contents" @if (! empty($item['href'])) href="{{ $item['href'] }}" @endif>
                        <div class="nx-elastic-cover" @if ($style) style="{{ $style }}" @endif>@if (! empty($item['image']))<img src="{{ $item['image'] }}" alt="{{ $item['alt'] ?? '' }}" loading="lazy">@endif</div>
                        @if (! empty($item['title']) || ! empty($item['caption']))
                            <figcaption class="nx-elastic-caption">@if (! empty($item['title']))<span>{{ $item['title'] }}</span>@endif @if (! empty($item['caption']))<small>{{ $item['caption'] }}</small>@endif</figcaption>
                        @endif
                        </{{ $wrap }}>
                    </figure>
                @endif
            @endforeach
        </div>
    @endforeach
</div>
