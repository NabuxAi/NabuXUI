{{--
    <x-nx::code-block filename="app/Models/Order.php" language="php" highlight="4, 9-11" :code="$source" />
    <x-nx::code-block language="diff" :code="$patch" />   (+ / - lines coloured)
    <x-nx::code-block language="ts" :code="$snippet" diff wrap :line-numbers="false" max-height="20rem" />

    Always left-to-right, even in an RTL page. Lines are rendered here; the Alpine part
    paints a light syntax colouring over them (strings, comments, keywords, numbers…).
    Copy reuses <x-nx::copy-button>; the wrap toggle soft-wraps long lines.
--}}
@props(['code' => null, 'language' => '', 'filename' => null, 'highlight' => null, 'diff' => null, 'lineNumbers' => true, 'startLine' => 1, 'wrap' => false, 'copy' => true, 'maxHeight' => null, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $words = array_merge(['wrap' => 'Wrap lines', 'code' => 'Code'], is_array($labels) ? $labels : []);
    $source = $code !== null ? (string) $code : html_entity_decode(trim((string) $slot), ENT_QUOTES | ENT_HTML5);
    $source = str_replace(["\r\n", "\r"], "\n", $source);
    $lines = $source === '' ? [] : explode("\n", $source);
    if (count($lines) > 0 && end($lines) === '') array_pop($lines);
    $language = (string) $language;
    $isDiff = $diff ?? strtolower($language) === 'diff';

    // "3, 7-9" or [3, [7, 9]] → [3 => true, 7 => true, …]
    $marked = [];
    $add = function ($from, $to) use (&$marked) {
        [$a, $b] = $from <= $to ? [$from, $to] : [$to, $from];
        for ($n = max(1, (int) $a); $n <= (int) $b && $n - $a < 10000; $n++) $marked[$n] = true;
    };
    if (is_string($highlight)) {
        foreach (preg_split('/[,\s]+/', $highlight) ?: [] as $piece) {
            if (preg_match('/^(\d+)\s*[-–]\s*(\d+)$/u', $piece, $m)) $add((int) $m[1], (int) $m[2]);
            elseif (ctype_digit($piece)) $add((int) $piece, (int) $piece);
        }
    } elseif (is_array($highlight)) {
        foreach ($highlight as $item) is_array($item) ? $add((int) $item[0], (int) $item[1]) : $add((int) $item, (int) $item);
    }

    $rows = [];
    foreach ($lines as $i => $raw) {
        $mark = null;
        if ($isDiff && preg_match('/^\+(?!\+\+)/', $raw)) $mark = 'add';
        elseif ($isDiff && preg_match('/^-(?!--)/', $raw)) $mark = 'del';
        $text = $isDiff && ($mark || str_starts_with($raw, ' ')) ? substr($raw, 1) : $raw;
        $rows[] = ['n' => (int) $startLine + $i, 'mark' => $mark, 'text' => $text];
    }
    $paintAs = $isDiff && strtolower($language) === 'diff' ? 'text' : $language;
@endphp
<div {{ $attributes->class('nx-code')->merge([
        'dir' => 'ltr',
        'wire:key' => 'nx-code-'.md5($source.'|'.$language),
        'data-diff' => $isDiff ? '' : null,
        'data-numbers' => $lineNumbers ? null : 'false',
        'data-wrap' => $wrap ? '' : null,
        'style' => $maxHeight ? "--nx-code-height: {$maxHeight}" : null,
    ]) }}
    x-data="nxCodeBlock(@js(['language' => $paintAs, 'wrap' => (bool) $wrap]))" x-bind:data-wrap="wrap ? '' : null">
    <div class="nx-code-head">
        @if ($filename)
            <span class="nx-code-file">{{ NabuXUI::icon('file') }}{{ $filename }}</span>
        @endif
        @if ($language !== '')
            <span class="nx-code-lang">{{ $language }}</span>
        @endif
        <span class="nx-code-tools">
            <button type="button" class="nx-agent-icon-button" aria-label="{{ $words['wrap'] }}" title="{{ $words['wrap'] }}"
                aria-pressed="{{ $wrap ? 'true' : 'false' }}" x-bind:aria-pressed="wrap ? 'true' : 'false'" x-on:click="wrap = ! wrap">
                {{ NabuXUI::icon('menu') }}
            </button>
            @if ($copy)
                <x-nx::copy-button :value="$source" size="sm" />
            @endif
        </span>
    </div>
    <div class="nx-code-body" tabindex="0" role="region" aria-label="{{ $filename ?? ($language !== '' ? $language : $words['code']) }}">
        <pre class="nx-code-pre"><code wire:ignore>@foreach ($rows as $row)<span class="nx-code-line" @if (isset($marked[$row['n']])) data-highlight @endif @if ($row['mark']) data-mark="{{ $row['mark'] }}" @endif><span class="nx-code-num" aria-hidden="true">{{ $row['n'] }}</span><span class="nx-code-sign" aria-hidden="true">{{ $row['mark'] === 'add' ? '+' : ($row['mark'] === 'del' ? '-' : ' ') }}</span><span class="nx-code-text">{{ $row['text'] }}
</span></span>@endforeach</code></pre>
    </div>
</div>
