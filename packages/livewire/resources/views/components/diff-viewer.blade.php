{{--
    <x-nx::diff-viewer filename="config/app.php" :old-text="$before" :new-text="$after" view="split" :context="3" />

    A line diff (Myers, computed in the browser by the core) shown unified or side by
    side. Unchanged runs longer than the context fold into "Expand N unchanged lines";
    the header counts additions and deletions. Always left-to-right.
--}}
@props(['oldText' => '', 'newText' => '', 'filename' => null, 'view' => 'unified', 'context' => 3, 'maxHeight' => null, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $words = array_merge([
        'unified' => 'Unified', 'split' => 'Split', 'expand' => 'Expand :count unchanged lines',
        'noChanges' => 'No changes', 'summary' => ':added additions, :removed deletions', 'changes' => 'Changes',
    ], is_array($labels) ? $labels : []);
    $view = $view === 'split' ? 'split' : 'unified';
    $config = ['oldText' => (string) $oldText, 'newText' => (string) $newText, 'view' => $view, 'context' => max(0, (int) $context), 'expand' => $words['expand']];
@endphp
<div {{ $attributes->class('nx-diff')->merge([
        'dir' => 'ltr',
        'wire:key' => 'nx-diff-'.md5($config['oldText'].'|'.$config['newText']),
        'style' => $maxHeight ? "--nx-diff-height: {$maxHeight}" : null,
    ]) }}
    x-data="nxDiffViewer(@js($config))">
    <div class="nx-diff-head">
        @if ($filename)
            <span class="nx-diff-file">{{ NabuXUI::icon('file') }}{{ $filename }}</span>
        @endif
        <span class="nx-diff-stats" role="img"
            x-bind:aria-label="@js($words['summary']).replace(':added', stats.added).replace(':removed', stats.removed)">
            <span class="nx-diff-stat" data-kind="add" x-text="'+' + stats.added"></span>
            <span class="nx-diff-stat" data-kind="del" x-text="'−' + stats.removed"></span>
        </span>
        <span class="nx-diff-views" role="group">
            <button type="button" class="nx-diff-view" x-bind:aria-pressed="mode === 'unified' ? 'true' : 'false'" aria-pressed="{{ $view === 'unified' ? 'true' : 'false' }}" x-on:click="mode = 'unified'">{{ $words['unified'] }}</button>
            <button type="button" class="nx-diff-view" x-bind:aria-pressed="mode === 'split' ? 'true' : 'false'" aria-pressed="{{ $view === 'split' ? 'true' : 'false' }}" x-on:click="mode = 'split'">{{ $words['split'] }}</button>
        </span>
    </div>
    <div class="nx-diff-body" tabindex="0" role="region" aria-label="{{ $filename ?? $words['changes'] }}">
        <p class="nx-diff-empty" x-show="stats.added + stats.removed === 0" x-cloak>{{ $words['noChanges'] }}</p>
        <div class="nx-diff-table" x-bind:data-view="mode" data-view="{{ $view }}">
            <template x-for="row in rows" :key="mode + row.key">
                <div style="display: contents">
                    <template x-if="row.type === 'gap'">
                        <button type="button" class="nx-diff-gap" x-on:click="expanded = [...expanded, row.gap]">
                            {{ NabuXUI::icon('chevron-down') }}<span x-text="expandLabel(row.count)"></span>
                        </button>
                    </template>
                    <template x-if="row.type === 'line'">
                        <div class="nx-diff-row" x-bind:data-kind="row.line.kind">
                            <span class="nx-diff-num" x-text="row.line.oldNo ?? ''"></span>
                            <span class="nx-diff-num" x-text="row.line.newNo ?? ''"></span>
                            <span class="nx-diff-sign" x-text="sign(row.line)"></span>
                            <span class="nx-diff-text" x-text="row.line.text"></span>
                        </div>
                    </template>
                    <template x-if="row.type === 'pair'">
                        <div class="nx-diff-row">
                            <span class="nx-diff-num nx-diff-cell" x-bind:data-kind="row.left ? row.left.kind : 'empty'" x-text="row.left ? row.left.oldNo : ''"></span>
                            <span class="nx-diff-text nx-diff-cell" x-bind:data-kind="row.left ? row.left.kind : 'empty'" x-text="row.left ? row.left.text : ''"></span>
                            <span class="nx-diff-num nx-diff-cell" x-bind:data-kind="row.right ? row.right.kind : 'empty'" x-text="row.right ? row.right.newNo : ''"></span>
                            <span class="nx-diff-text nx-diff-cell" x-bind:data-kind="row.right ? row.right.kind : 'empty'" x-text="row.right ? row.right.text : ''"></span>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
