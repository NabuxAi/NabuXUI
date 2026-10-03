{{--
    <x-nx::tool-call name="search_web" :args="['query' => 'Tehran weather', 'limit' => 5]"
        status="success" :output="$results" :duration="840" />

    status: queued | running | success | error — the status icon swaps in place (spinner,
    check, alert) and the pill says it in words. The row opens to the full input and
    output as pretty JSON (strings are shown as they are). Re-render with a new status
    from Livewire, or bind data-status from outside; the Alpine part follows it.
--}}
@props(['name', 'args' => null, 'status' => 'success', 'input' => null, 'output' => null, 'duration' => null, 'open' => false, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $id = NabuXUI::id('nx-tool');
    $locale = str_replace('_', '-', app()->getLocale());
    $words = array_merge(['input' => 'Input', 'output' => 'Output', 'queued' => 'Queued', 'running' => 'Running', 'success' => 'Done', 'error' => 'Failed'], is_array($labels) ? $labels : []);
    $status = in_array($status, ['queued', 'running', 'success', 'error'], true) ? $status : 'success';

    $literal = fn ($value) => match (true) {
        is_string($value) => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        is_bool($value) => $value ? 'true' : 'false',
        $value === null => 'null',
        default => (string) $value,
    };
    $preview = function ($args, int $max = 72) use ($literal) {
        if ($args === null || $args === '') return '';
        if (is_string($args)) $text = $args;
        elseif (is_array($args) && ! array_is_list($args)) {
            $text = implode(', ', array_map(
                fn ($key, $value) => $key.': '.(is_array($value) ? (array_is_list($value) ? '['.count($value).']' : '{…}') : $literal($value)),
                array_keys($args), $args,
            ));
        } else $text = json_encode($args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $text = trim(preg_replace('/\s+/u', ' ', (string) $text));
        return mb_strlen($text) > $max ? rtrim(mb_substr($text, 0, $max - 1)).'…' : $text;
    };
    $pretty = fn ($value) => is_string($value) ? $value : json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $shownInput = $input ?? $args;
    $time = $duration === null ? null : ($duration < 1000
        ? NabuXUI::formatNumber((int) round($duration), 0, $locale).'ms'
        : NabuXUI::formatNumber($duration / 1000, 1, $locale).'s');
    $icons = [
        'queued' => '<span class="nx-thinking-dot"></span>',
        'running' => '<span class="nx-spinner"></span>',
        'success' => (string) NabuXUI::icon('check-circle'),
        'error' => (string) NabuXUI::icon('alert-circle'),
    ];
@endphp
<div {{ $attributes->class('nx-tool')->merge(['data-status' => $status]) }} x-data="nxToolCall(@js((bool) $open))">
    <button type="button" class="nx-tool-head" aria-controls="{{ $id }}-panels"
        aria-expanded="{{ $open ? 'true' : 'false' }}" x-bind:aria-expanded="open ? 'true' : 'false'" x-on:click="open = ! open">
        <span class="nx-tool-status nx-agent-swap" aria-hidden="true">
            @foreach ($icons as $key => $svg)
                <span data-icon="{{ $key }}" @if ($status === $key) data-on @endif x-bind:data-on="status === @js($key) ? '' : null">{!! $svg !!}</span>
            @endforeach
        </span>
        <span class="nx-tool-name">{{ $name }}</span>
        <span class="nx-tool-args" dir="ltr">{{ $preview($args) }}</span>
        @if ($time !== null)
            <span class="nx-tool-time" x-show="status === 'success' || status === 'error'">{{ $time }}</span>
        @endif
        <span class="nx-tool-state" x-text="@js($words)[status]">{{ $words[$status] }}</span>
        {{ NabuXUI::icon('chevron-down', 'nx-tool-chevron') }}
    </button>
    <div id="{{ $id }}-panels" class="nx-agent-collapse" @if ($open) data-open @endif x-bind:data-open="open ? '' : null">
        <div>
            <div class="nx-tool-panels" dir="ltr">
                @if ($shownInput !== null)
                    <div class="nx-tool-section">
                        <p class="nx-tool-label">{{ $words['input'] }}</p>
                        <pre class="nx-tool-pre">{{ $pretty($shownInput) }}</pre>
                    </div>
                @endif
                @if ($output !== null)
                    <div class="nx-tool-section">
                        <p class="nx-tool-label">{{ $words['output'] }}</p>
                        <pre class="nx-tool-pre">{{ $pretty($output) }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
