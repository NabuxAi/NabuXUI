{{--
    <x-nx::scroll-text-reveal text="Every word finds its place" eyebrow="Scroll · Desplázate" height="240vh" />

    A tall section with a pinned stage: scattered letters gather as it scrolls and the
    filled text wipes in over its outline. CSS scroll-driven animations drive it, and
    Alpine (nxScrollTextReveal) writes --nx-progress where they are missing. `seed`
    scatters the letters another way; reduced motion shows the finished text.
--}}
@props(['text' => '', 'eyebrow' => null, 'height' => '240vh', 'seed' => 1, 'as' => 'h2'])
@php
    // Letters grouped by word, as the core's splitGlyphs() does it.
    $text = trim((string) $text);
    $shapedScript = '/[\x{0600}-\x{06FF}\x{0700}-\x{074F}\x{0750}-\x{077F}\x{07C0}-\x{07FF}\x{08A0}-\x{08FF}\x{0900}-\x{0DFF}\x{0E00}-\x{0EFF}\x{0F00}-\x{0FFF}\x{1000}-\x{109F}\x{1780}-\x{17FF}\x{1800}-\x{18AF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
    $looseScript = '/[\x{3000}-\x{30FF}\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{F900}-\x{FAFF}\x{FF00}-\x{FFEF}]/u';
    $strong = '/[\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}]|(?![\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}])\p{L}/u';
    $dir = \NabuXUI\NabuXUI::direction($text);

    // Where each letter starts: the core's scatterGlyphs(), a Park–Miller stream whose
    // products stay below 2^53, so PHP and JavaScript compute the same integers.
    $state = abs((int) $seed) % 2147483647 ?: 1;
    $next = function () use (&$state) {
        $state = ($state * 48271) % 2147483647;

        return $state % 2001 - 1000;
    };
    $spread = fn (int $value) => ($value < 0 ? -1 : 1) * (350 + intdiv(abs($value) * 13, 20));

    $letters = '';
    $index = 0;
    foreach (\NabuXUI\NabuXUI::split($text, 'word') as $chunk) {
        $body = preg_replace('/\s+$/u', '', $chunk);
        preg_match_all(preg_match($shapedScript, $body) ? '/\S+\s*/u' : '/\X/u', $body, $found);
        $own = \NabuXUI\NabuXUI::direction($body);
        $wordDir = preg_match($strong, $body) ? ($own === $dir ? null : $own) : ($dir === 'rtl' && preg_match('/[0-9]/', $body) ? 'ltr' : null);
        if ($found[0]) {
            $letters .= '<span class="nx-glyph-word"'.($wordDir ? ' dir="'.$wordDir.'"' : '').(preg_match($looseScript, $body) ? ' data-loose' : '').'>';
            foreach ($found[0] as $piece) {
                $x = $spread($next());
                $y = $spread($next());
                $r = $next();
                $letters .= '<span class="nx-scroll-reveal-char" style="--nx-i: '.$index.'; --_x: '.$x.'; --_y: '.$y.'; --_r: '.$r.'">'.e($piece).'</span>';
                $index++;
            }
            $letters .= '</span>';
        }
        $letters .= e(substr($chunk, strlen($body)));
    }
@endphp
<section {{ $attributes->class('nx-scroll-reveal')->merge(['style' => "--nx-scroll-height: {$height}"]) }} x-data="nxScrollTextReveal" wire:ignore.self>
    <div class="nx-scroll-reveal-stage">
        @if ($eyebrow)<p class="nx-scroll-reveal-eyebrow">{{ $eyebrow }}</p>@endif
        <{{ $as }} class="nx-scroll-reveal-title" @if (preg_match($shapedScript, $text)) data-shaped @endif>
            <span class="nx-visually-hidden">{{ $text }}</span>
            <span class="nx-scroll-reveal-art" aria-hidden="true" style="--_n: {{ $index }}"><span class="nx-scroll-reveal-layer" data-layer="outline" dir="{{ $dir }}">{!! $letters !!}</span><span class="nx-scroll-reveal-layer" data-layer="fill" dir="{{ $dir }}">{!! $letters !!}</span></span>
        </{{ $as }}>
    </div>
</section>
