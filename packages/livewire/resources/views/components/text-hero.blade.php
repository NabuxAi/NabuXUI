{{--
    <x-nx::text-hero title="Write in every language" highlight="every language" subtitle="…"
        :stickers="[['shape' => 'star', 'position' => 'top-start'], ['shape' => 'heart', 'position' => 'bottom-end', 'tone' => 'pink']]">
        <x-slot:actions> <x-nx::button variant="primary">Start writing</x-nx::button> </x-slot:actions>
    </x-nx::text-hero>

    The title's letters spring up one by one (Persian and Arabic: word by word) and the
    highlight takes the brand gradient. Stickers pop in around the title, then float:
    shape star | sparkle | heart | bolt | smiley | arrow · position top-start | top-end |
    bottom-start | bottom-end | start | end · tone accent | gold | violet | cyan | pink.
--}}
@props(['title' => '', 'highlight' => null, 'subtitle' => null, 'stickers' => [], 'align' => 'center', 'as' => 'h1'])
@php
    // Letters grouped by word, as the core's splitGlyphs() does it: a word never breaks
    // apart, a word written the other way gets its own dir, shaped scripts move by word.
    $text = trim((string) $title);
    $mark = trim((string) $highlight);
    $shapedScript = '/[\x{0600}-\x{06FF}\x{0700}-\x{074F}\x{0750}-\x{077F}\x{07C0}-\x{07FF}\x{08A0}-\x{08FF}\x{0900}-\x{0DFF}\x{0E00}-\x{0EFF}\x{0F00}-\x{0FFF}\x{1000}-\x{109F}\x{1780}-\x{17FF}\x{1800}-\x{18AF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
    $looseScript = '/[\x{3000}-\x{30FF}\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{F900}-\x{FAFF}\x{FF00}-\x{FFEF}]/u';
    $strong = '/[\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}]|(?![\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}])\p{L}/u';
    $dir = \NabuXUI\NabuXUI::direction($text);
    $from = $mark !== '' ? strpos($text, $mark) : false;
    $to = $from === false ? -1 : $from + strlen($mark);
    $letters = '';
    $index = 0;
    $marked = 0;
    $offset = 0;
    foreach (\NabuXUI\NabuXUI::split($text, 'word') as $chunk) {
        $body = preg_replace('/\s+$/u', '', $chunk);
        preg_match_all(preg_match($shapedScript, $body) ? '/\S+\s*/u' : '/\X/u', $body, $found);
        $own = \NabuXUI\NabuXUI::direction($body);
        $wordDir = preg_match($strong, $body) ? ($own === $dir ? null : $own) : ($dir === 'rtl' && preg_match('/[0-9]/', $body) ? 'ltr' : null);
        if ($found[0]) {
            $letters .= '<span class="nx-glyph-word"'.($wordDir ? ' dir="'.$wordDir.'"' : '').(preg_match($looseScript, $body) ? ' data-loose' : '').'>';
            $at = $offset;
            foreach ($found[0] as $piece) {
                $inside = $from !== false && $at >= $from && $at < $to;
                $letters .= '<span class="nx-text-hero-char"'.($inside ? ' data-mark style="--nx-i: '.$index.'; --_k: '.$marked++.'"' : ' style="--nx-i: '.$index.'"').'>'.e($piece).'</span>';
                $index++;
                $at += strlen($piece);
            }
            $letters .= '</span>';
        }
        $letters .= e(substr($chunk, strlen($body)));
        $offset += strlen($chunk);
    }

    // The stickers, drawn for NabuXUI (the same paths as the core's stickerArt).
    $art = [
        'star' => ['body' => 'M32 7L39 23.3 56.7 25 43.4 36.7 47.3 54 32 45 16.7 54 20.6 36.7 7.3 25 25 23.3Z', 'shine' => 'M25.5 29l1.8-3.8', 'tone' => 'gold'],
        'sparkle' => ['body' => 'M30 7C31.8 23.5 38.5 30.2 55 32 38.5 33.8 31.8 40.5 30 57 28.2 40.5 21.5 33.8 5 32 21.5 30.2 28.2 23.5 30 7ZM52 5c.5 4.5 2.5 6.5 7 7-4.5.5-6.5 2.5-7 7-.5-4.5-2.5-6.5-7-7 4.5-.5 6.5-2.5 7-7Z', 'tone' => 'violet'],
        'heart' => ['body' => 'M32 55C18.5 46 8 37 8 24.5 8 16.5 14 10.5 21.5 10.5c4.7 0 8.3 2.5 10.5 6.3 2.2-3.8 5.8-6.3 10.5-6.3C50 10.5 56 16.5 56 24.5 56 37 45.5 46 32 55Z', 'shine' => 'M16.5 21c1-2.6 3-4.1 5.5-4.3', 'tone' => 'pink'],
        'bolt' => ['body' => 'M36 4 12 36h17l-4 24 27-33H36l7-23Z', 'shine' => 'M31.5 12l-4.5 7', 'tone' => 'accent'],
        'smiley' => ['body' => 'M32 6a26 26 0 1 1 0 52 26 26 0 1 1 0-52Z', 'ink' => 'M24.5 24v5M39.5 24v5M21.5 37c3.5 6.5 17.5 6.5 21 0', 'shine' => 'M15.5 24c1.2-3.6 3.6-6.3 7-7.8', 'tone' => 'gold'],
        'arrow' => ['body' => 'M8 50c12 1.5 23-4.5 30-15.5 3.8-6 8-12.6 17-16.5M44.5 13.5 56 18.5l-5 11', 'line' => true, 'tone' => 'cyan'],
    ];
@endphp
<section {{ $attributes->class('nx-hero nx-text-hero')->merge([
    'data-align' => $align === 'start' ? 'start' : null,
    'data-shaped' => preg_match($shapedScript, $text) ? '' : null,
    'data-nx-reveal' => '',
    'style' => "--_n: {$index}; --_m: {$marked}",
]) }} x-data x-nx-reveal wire:ignore.self>
    <div class="nx-hero-content">
        <div class="nx-text-hero-heading">
            <{{ $as }} class="nx-hero-title nx-text-hero-title">
                <span class="nx-visually-hidden">{{ $text }}</span>
                <span aria-hidden="true" dir="{{ $dir }}">{!! $letters !!}</span>
            </{{ $as }}>
            @foreach (array_values($stickers) as $i => $sticker)
                @php
                    $shape = isset($art[$sticker['shape'] ?? '']) ? $sticker['shape'] : 'star';
                    $shapeArt = $art[$shape];
                @endphp
                <span class="nx-text-hero-sticker" data-shape="{{ $shape }}" data-position="{{ $sticker['position'] ?? 'top-start' }}" data-tone="{{ $sticker['tone'] ?? $shapeArt['tone'] }}" style="--nx-i: {{ $i }}" aria-hidden="true"><svg viewBox="0 0 64 64" focusable="false">@if (! empty($shapeArt['line']))<path data-part="outline" d="{{ $shapeArt['body'] }}"/><path data-part="line" d="{{ $shapeArt['body'] }}"/>@else<path data-part="body" d="{{ $shapeArt['body'] }}"/>@endif @isset($shapeArt['ink'])<path data-part="ink" d="{{ $shapeArt['ink'] }}"/>@endisset @isset($shapeArt['shine'])<path data-part="shine" d="{{ $shapeArt['shine'] }}"/>@endisset</svg></span>
            @endforeach
        </div>
        @if ($subtitle)<p class="nx-hero-subtitle nx-text-hero-subtitle">{{ $subtitle }}</p>@endif
        @isset($actions)<div class="nx-hero-actions nx-text-hero-actions">{{ $actions }}</div>@endisset
        {{ $slot }}
    </div>
</section>
