{{--
    <x-nx::tree-view :nodes="[
        ['id' => 'src', 'label' => 'src', 'icon' => 'folder', 'children' => [
            ['id' => 'css', 'label' => 'css', 'meta' => '4 files', 'children' => [
                ['id' => 'tree', 'label' => 'tree-view.css', 'icon' => 'file', 'tone' => 'info'],
            ]],
        ]],
        ['id' => 'docs', 'label' => 'docs', 'meta' => '3 files'],
    ]" :default-expanded="['src']" selected="docs" />

    A collapsible tree on the ARIA treeview pattern: one tab stop, ↑/↓ walk
    the visible items, →/← expand and collapse (swapped in RTL), Home/End jump
    to the ends, Enter selects and Space folds a parent. The twist chevron is
    the pointer affordance for the fold; the label selects. Parent rows carry
    their child count, and the fold is a state change — chevron, colour and
    the group itself all move together.

    State: the nodes you pass are the truth (they are never edited here).
    Selection follows wire:model / x-model when bound, and every change also
    bubbles as nx-select; folds stay client-side and bubble as
    nx-expand ({ id, open }).
--}}
@props(['nodes' => [], 'defaultExpanded' => [], 'selected' => null, 'selectable' => true, 'label' => null, 'locale' => null])
@php
    use NabuXUI\NabuXUI;
    // Kebab-case boolean props arrive as strings; read them like Blade does.
    $selectable = ! in_array(strtolower((string) $selectable), ['0', 'false', 'no', 'off', ''], true);
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The tree's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $labels = [
        'tree' => $label ?? $say('treeView'),
        'children' => $say('treeChildren'),
        'selected' => $say('treeSelected'),
        'empty' => $say('treeEmpty'),
    ];
    $iconOf = fn ($name) => is_string($name) && NabuXUI::hasIcon($name) ? NabuXUI::icon($name) : '';
    $toneOf = fn ($tone) => in_array($tone, ['accent', 'success', 'warning', 'danger', 'info', 'gold'], true) ? $tone : null;

    // The ids of the parents that start open.
    $expandedIds = [];
    foreach ((array) $defaultExpanded as $id) {
        if (is_scalar($id)) {
            $expandedIds[] = (string) $id;
        }
    }
    $selectedId = is_scalar($selected) ? (string) $selected : null;

    // One node, clean; children recursively.
    $normalize = null;
    $normalize = function ($node) use (&$normalize, $iconOf, $toneOf) {
        if (! is_array($node) || ! isset($node['id']) || ! is_scalar($node['id'])) {
            return null;
        }
        $children = [];
        foreach ($node['children'] ?? [] as $child) {
            $clean = $normalize($child);
            if ($clean !== null) {
                $children[] = $clean;
            }
        }

        return [
            'id' => (string) $node['id'],
            'label' => (string) ($node['label'] ?? $node['id']),
            'meta' => isset($node['meta']) && is_scalar($node['meta']) ? (string) $node['meta'] : null,
            'icon' => $iconOf($node['icon'] ?? null),
            'tone' => $toneOf($node['tone'] ?? null),
            'children' => $children,
        ];
    };
    $tree = [];
    foreach ((array) $nodes as $node) {
        $clean = $normalize($node);
        if ($clean !== null) {
            $tree[] = $clean;
        }
    }

    // The roving tab stop starts on the selection, or on the first row.
    $allIds = [];
    $collect = null;
    $collect = function (array $nodes) use (&$collect, &$allIds) {
        foreach ($nodes as $node) {
            $allIds[] = $node['id'];
            $collect($node['children']);
        }
    };
    $collect($tree);
    $activeId = $selectedId !== null && in_array($selectedId, $allIds, true) ? $selectedId : ($tree[0]['id'] ?? null);

    // The tree, rendered recursively: rows carry the ARIA state Alpine keeps
    // honest; ids are server-made; text is escaped, data is never markup.
    $treeId = NabuXUI::id('nx-tree-view');
    $render = null;
    $render = function (array $nodes, int $level) use (&$render, $labels, $expandedIds, $selectable, $selectedId, $activeId, $treeId, $locale): string {
        $html = '';
        $size = count($nodes);
        foreach ($nodes as $i => $node) {
            $id = $node['id'];
            $children = $node['children'];
            $hasChildren = count($children) > 0;
            $isOpen = $hasChildren && in_array($id, $expandedIds, true);
            $key = "{$treeId}-{$id}";
            $jsId = json_encode($id);

            $html .= '<li class="nx-tree-view-item" role="treeitem" data-id="'.e($id).'"';
            $html .= ' aria-level="'.$level.'" aria-setsize="'.$size.'" aria-posinset="'.($i + 1).'"';
            $html .= ' style="--nx-i: '.$i.'"';
            if ($node['tone'] !== null) {
                $html .= ' data-tone="'.e($node['tone']).'"';
            }
            if ($hasChildren) {
                $html .= ' aria-expanded="'.($isOpen ? 'true' : 'false').'"';
                $html .= ' x-bind:aria-expanded="isOpen('.$jsId.') ? \'true\' : \'false\'"';
            }
            if ($selectable) {
                $html .= ' aria-selected="'.($selectedId === $id ? 'true' : 'false').'"';
                $html .= ' x-bind:aria-selected="selected === '.$jsId.' ? \'true\' : \'false\'"';
            }
            $html .= ' tabindex="'.($activeId === $id ? '0' : '-1').'"';
            $html .= ' x-bind:tabindex="active === '.$jsId.' ? 0 : -1"';
            $html .= '>';

            $html .= '<div class="nx-tree-view-row" x-on:click="rowClick('.$jsId.', $event)">';
            $html .= '<span class="nx-tree-view-twist"'.($hasChildren ? '' : ' data-leaf=""').' aria-hidden="true">';
            if ($hasChildren) {
                $html .= NabuXUI::icon('chevron-right');
            }
            $html .= '</span>';
            if ($node['icon'] !== '') {
                $html .= '<span class="nx-tree-view-icon">'.$node['icon'].'</span>';
            }
            $html .= '<span class="nx-tree-view-label" id="'.e($key).'-label">'.e($node['label']).'</span>';
            if ($node['meta'] !== null) {
                $html .= '<span class="nx-tree-view-meta">'.e($node['meta']).'</span>';
            }
            if ($hasChildren) {
                $count = count($children);
                $html .= '<span class="nx-tree-view-count" aria-hidden="true"><span class="nx-number">'.e(NabuXUI::formatNumber($count, 0, $locale)).'</span></span>';
                $html .= '<span class="nx-visually-hidden">'.e(str_replace(':count', NabuXUI::formatNumber($count, 0, $locale), $labels['children'])).'</span>';
            }
            $html .= '</div>';

            if ($hasChildren) {
                $html .= '<div class="nx-tree-view-group">';
                $html .= '<ul class="nx-tree-view-group-list" role="group" aria-labelledby="'.e($key).'-label">';
                $html .= $render($children, $level + 1);
                $html .= '</ul></div>';
            }
            $html .= '</li>';
        }

        return $html;
    };
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
    $config = ['nodes' => $tree, 'labels' => $labels, 'locale' => $locale, 'defaultExpanded' => $expandedIds, 'selected' => $selectedId, 'selectable' => $selectable];
@endphp
<div {{ $rest->class('nx-tree-view-root') }} {{ $listenerAttrs }}
    x-data="nxTreeView(@js($config))"
    x-modelable="model"
    x-on:keydown="key($event)"
    x-on:focusin.capture="focusIn($event)">
    <ul class="nx-tree-view" role="tree" aria-label="{{ $labels['tree'] }}">
        @if (count($tree) === 0)
            <li class="nx-tree-view-empty" role="none">{{ $labels['empty'] }}</li>
        @endif
        {!! $render($tree, 1) !!}
    </ul>
    {{-- The selection, spoken once it lands. --}}
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
</div>
