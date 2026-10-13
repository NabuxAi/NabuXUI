{{--
    <x-nx::roll-text href="/work">Work</x-nx::roll-text>                       a link
    <x-nx::roll-text as="button" wire:click="open">Menu</x-nx::roll-text>      a button
    <a href="/" class="…"><x-nx::roll-text>Home</x-nx::roll-text></a>           rolls with its parent

    On hover each letter rolls up and out while its copy rolls in from below, in reading
    order (Persian and Arabic: word by word). The copy is aria-hidden; the name stays the
    text. Set --nx-roll-color for the copy's colour; `static` drops the press scaling.
--}}
@props(['href' => null, 'as' => 'span', 'type' => 'button', 'static' => false])
@php
    // Letters grouped by word, as the core's splitGlyphs() does it.
    $text = html_entity_decode(trim(strip_tags((string) $slot)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $shapedScript = '/[\x{0600}-\x{06FF}\x{0700}-\x{074F}\x{0750}-\x{077F}\x{07C0}-\x{07FF}\x{08A0}-\x{08FF}\x{0900}-\x{0DFF}\x{0E00}-\x{0EFF}\x{0F00}-\x{0FFF}\x{1000}-\x{109F}\x{1780}-\x{17FF}\x{1800}-\x{18AF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
    $looseScript = '/[\x{3000}-\x{30FF}\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{F900}-\x{FAFF}\x{FF00}-\x{FFEF}]/u';
    $strong = '/[\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}]|(?![\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}])\p{L}/u';
    $dir = \NabuXUI\NabuXUI::direction($text);
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
                $letters .= '<span class="nx-roll-text-piece" style="--nx-i: '.$index++.'"><span>'.e($piece).'</span><span>'.e($piece).'</span></span>';
            }
            $letters .= '</span>';
        }
        $letters .= e(substr($chunk, strlen($body)));
    }
    $tag = $href ? 'a' : ($as === 'button' ? 'button' : 'span');
@endphp
<{{ $tag }} {{ $attributes->class('nx-roll-text')->merge(['href' => $href, 'type' => $tag === 'button' ? $type : null, 'data-static' => $static ? '' : null]) }}><span class="nx-visually-hidden">{{ $text }}</span><span class="nx-roll-text-pieces" aria-hidden="true" dir="{{ $dir }}">{!! $letters !!}</span></{{ $tag }}>
