{{--
    <x-nx::json-viewer :data="$response" :depth="2" max-height="24rem" />

    A collapsible JSON tree: type colours, child counts on folded nodes, expand/collapse
    all, search that opens the way to every hit and marks it, and per-row copy of the
    JS path ($.items[0].name) or the value. Always left-to-right.
--}}
@props(['data' => null, 'depth' => 1, 'searchable' => true, 'maxHeight' => null, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $words = array_merge([
        'search' => 'Search keys and values', 'expandAll' => 'Expand all', 'collapseAll' => 'Collapse all',
        'copyPath' => 'Copy path', 'copyValue' => 'Copy value', 'items' => ':count items', 'keys' => ':count keys',
        'matches' => ':count found', 'expand' => 'Expand', 'collapse' => 'Collapse',
    ], is_array($labels) ? $labels : []);
    // Strings of JSON are parsed; anything else is taken as data already.
    if (is_string($data)) {
        $decoded = json_decode($data, true);
        $data = json_last_error() === JSON_ERROR_NONE ? $decoded : $data;
    }
    $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $config = ['data' => $data, 'depth' => max(0, (int) $depth), 'items' => $words['items'], 'keys' => $words['keys'], 'matches' => $words['matches']];
@endphp
<div {{ $attributes->class('nx-json')->merge([
        'dir' => 'ltr',
        'wire:key' => 'nx-json-'.md5((string) $payload),
        'style' => $maxHeight ? "--nx-json-height: {$maxHeight}" : null,
    ]) }}
    x-data="nxJsonViewer(@js($config))">
    <div class="nx-json-bar">
        @if ($searchable)
            <label class="nx-json-search">
                {{ NabuXUI::icon('search') }}
                <input class="nx-json-search-input" type="search" placeholder="{{ $words['search'] }}" aria-label="{{ $words['search'] }}" x-model.debounce.120ms="query">
            </label>
            <span class="nx-json-found" aria-live="polite" x-text="query.trim() ? found : ''"></span>
        @endif
        <span class="nx-code-tools">
            <button type="button" class="nx-agent-icon-button" aria-label="{{ $words['expandAll'] }}" title="{{ $words['expandAll'] }}" x-on:click="expandAll()">{{ NabuXUI::icon('plus') }}</button>
            <button type="button" class="nx-agent-icon-button" aria-label="{{ $words['collapseAll'] }}" title="{{ $words['collapseAll'] }}" x-on:click="collapseAll()">{{ NabuXUI::icon('minus') }}</button>
        </span>
    </div>
    <ul class="nx-json-tree">
        <template x-for="row in rows" :key="row.id">
            <li class="nx-json-row" x-bind:style="'--_depth: ' + row.depth" x-bind:data-type="row.close ? null : row.type" x-bind:data-hit="hits.has(row.id) ? '' : null">
                <template x-if="! row.close && (row.type === 'object' || row.type === 'array') && row.count > 0">
                    <button type="button" class="nx-json-toggle" x-bind:aria-expanded="row.open ? 'true' : 'false'"
                        x-bind:aria-label="(row.open ? @js($words['collapse']) : @js($words['expand'])) + ' ' + (row.key ?? '$')" x-on:click="toggle(row.id)">
                        {{ NabuXUI::icon('chevron-down') }}
                    </button>
                </template>
                <template x-if="row.close || ! ((row.type === 'object' || row.type === 'array') && row.count > 0)">
                    <span class="nx-json-spacer"></span>
                </template>
                <template x-if="! row.close && row.key !== null">
                    <span>
                        <span class="nx-json-key"><template x-for="(part, p) in parts(keyText(row))" :key="p"><span><template x-if="part.hit"><mark x-text="part.text"></mark></template><template x-if="! part.hit"><span x-text="part.text"></span></template></span></template></span><span class="nx-json-punct">: </span>
                    </span>
                </template>
                <template x-if="row.close">
                    <span class="nx-json-punct" x-text="brace(row, true) + (row.last ? '' : ',')"></span>
                </template>
                <template x-if="! row.close && (row.type === 'object' || row.type === 'array')">
                    <span>
                        <span class="nx-json-punct" x-text="row.open ? brace(row, false) : brace(row, false) + (row.count ? '…' : '') + brace(row, true) + (row.last ? '' : ',')"></span>
                        <span class="nx-json-count" x-show="! row.open && row.count > 0" x-text="summary(row)"></span>
                    </span>
                </template>
                <template x-if="! row.close && row.type !== 'object' && row.type !== 'array'">
                    <span class="nx-json-value" x-bind:data-type="row.type"><template x-for="(part, p) in parts(row.literal)" :key="p"><span><template x-if="part.hit"><mark x-text="part.text"></mark></template><template x-if="! part.hit"><span x-text="part.text"></span></template></span></template><span class="nx-json-punct" x-text="row.last ? '' : ','"></span></span>
                </template>
                <template x-if="! row.close">
                    <span class="nx-json-actions">
                        <button type="button" class="nx-agent-icon-button" title="{{ $words['copyPath'] }}" x-bind:aria-label="@js($words['copyPath']) + ' ' + row.id"
                            x-bind:data-copied="copied === row.id + ':path' ? '' : null" x-on:click="copy(row, 'path')">
                            <span class="nx-agent-swap">{{ NabuXUI::icon('layers', 'nx-agent-swap-idle') }}{{ NabuXUI::icon('check', 'nx-agent-swap-done') }}</span>
                        </button>
                        <button type="button" class="nx-agent-icon-button" title="{{ $words['copyValue'] }}" x-bind:aria-label="@js($words['copyValue']) + ' ' + row.id"
                            x-bind:data-copied="copied === row.id + ':value' ? '' : null" x-on:click="copy(row, 'value')">
                            <span class="nx-agent-swap">{{ NabuXUI::icon('copy', 'nx-agent-swap-idle') }}{{ NabuXUI::icon('check', 'nx-agent-swap-done') }}</span>
                        </button>
                    </span>
                </template>
            </li>
        </template>
    </ul>
</div>
