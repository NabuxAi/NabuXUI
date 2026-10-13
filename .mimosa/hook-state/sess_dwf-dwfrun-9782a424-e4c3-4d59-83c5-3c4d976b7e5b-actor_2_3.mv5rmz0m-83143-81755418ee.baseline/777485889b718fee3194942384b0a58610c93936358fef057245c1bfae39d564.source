{{--
    <x-nx::file-manager :items="[
        ['id' => 'design', 'name' => 'طراحی', 'kind' => 'folder', 'items' => 12],
        ['id' => 'logo.svg', 'name' => 'logo.svg', 'parent' => 'design', 'size' => 18432, 'modified' => now()->subDays(2), 'href' => '#'],
        ['id' => 'headshot.png', 'name' => 'headshot.png', 'parent' => 'design', 'kind' => 'image', 'size' => 2411724,
            'preview' => 'https://…/headshot.png', 'details' => [['label' => 'دقت', 'value' => '۲۴۰۰×۱۶۰۰']]],
    ]" current="design" view="grid" height="30rem" />

    Every entry: ['id', 'name', 'parent' => the folder it lives in (null/omitted = root), 'kind' =>
    folder|image|audio|video|file (or let the extension say), 'size' => bytes, 'items' => folder count,
    'modified' => Carbon|ISO|timestamp|label, 'href', 'preview', 'details' => [['label','value']], 'meta' => override].
    Folders come first in every listing, names sorted by the reader's collation; the count rolls its
    digits and sizes/dates come out in the page's locale.

    State lives in Alpine: folders open in place (nx-navigate), the search filters the current folder
    (nx-results via the live region), the grid/list switch rides the stock segmented control (nx-view)
    and a file card slides its details drawer in from the inline end (nx-open; Escape and the scrim
    close it). The upload button opens a native file picker: with a wire:model on the component
    (e.g. <x-nx::file-manager wire:model="uploads">) Livewire takes the files; otherwise nx-upload
    bubbles with them. Everything still reads without JavaScript: the current folder's entries are
    the ones the server leaves unhidden.
--}}
@props(['items' => [], 'view' => 'grid', 'current' => null, 'height' => null, 'label' => null, 'searchPlaceholder' => null, 'emptyText' => null, 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The file manager's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $labels = [
        'board' => $label ?? $say('fileManager'),
        'search' => $say('search'),
        'searchPlaceholder' => $searchPlaceholder ?? $say('fmSearchPlaceholder'),
        'clearSearch' => $say('fmClearSearch'),
        'view' => $say('fmView'),
        'grid' => $say('fmGridView'),
        'list' => $say('fmListView'),
        'upload' => $say('fmUpload'),
        'items' => $say('fmItems'),
        'empty' => $emptyText ?? $say('fmEmpty'),
        'results' => $say('noResults'),
        'entered' => $say('fmEntered'),
        'details' => $say('fmDetails'),
        'detailsFor' => $say('fmDetailsFor'),
        'type' => $say('fmType'),
        'size' => $say('fmSize'),
        'modified' => $say('fmModified'),
        'contains' => $say('fmContains'),
        'open' => $say('fmOpen'),
        'close' => $say('close'),
        'breadcrumb' => $say('breadcrumb'),
        'kinds' => [
            'folder' => $say('fmKindFolder'),
            'image' => $say('fmKindImage'),
            'audio' => $say('fmKindAudio'),
            'video' => $say('fmKindVideo'),
            'file' => $say('fmKindFile'),
        ],
    ];

    $kindIcons = ['folder' => 'folder', 'image' => 'image', 'audio' => 'music', 'video' => 'play', 'file' => 'file'];

    // The kind an entry is: the one given, or the one its extension says.
    $derive = function ($name) {
        if (! preg_match('/\.([a-z0-9]+)$/i', (string) $name, $m)) {
            return 'file';
        }

        return match (strtolower($m[1])) {
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif', 'heic' => 'image',
            'mp3', 'wav', 'ogg', 'm4a', 'flac', 'aac' => 'audio',
            'mp4', 'mov', 'webm', 'avi', 'mkv' => 'video',
            default => 'file',
        };
    };
    $kindOf = function ($item) use ($derive) {
        return in_array($item['kind'] ?? null, ['folder', 'image', 'audio', 'video', 'file'], true) ? $item['kind'] : $derive($item['name'] ?? '');
    };

    // A moment from anything the app might hand us — or a plain label, shown as given.
    // The TRADITIONAL flag rides the reader's own calendar (Jalali for fa), as the React side's Intl does.
    $when = function ($modified) use ($locale) {
        if ($modified === null || $modified === '') {
            return null;
        }
        if ($modified instanceof \DateTimeInterface) {
            $at = $modified->getTimestamp();
        } elseif (is_numeric($modified)) {
            $at = (int) $modified;
        } elseif (is_string($modified) && ($parsed = strtotime($modified)) !== false) {
            $at = $parsed;
        } else {
            return is_string($modified) ? $modified : null;
        }
        if (class_exists(\IntlDateFormatter::class)) {
            return (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE, null, \IntlDateFormatter::TRADITIONAL))->format($at);
        }

        return date('Y/m/d', $at);
    };

    // "۲٫۴ MB" — locale digits, a thin space, a unit symbol either language reads.
    $bytes = function ($size) use ($locale) {
        if ($size === null || $size < 0) {
            return null;
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $value = (float) $size;
        $unit = 0;
        while ($value >= 1024 && $unit < count($units) - 1) {
            $value /= 1024;
            $unit++;
        }
        $decimals = ($unit === 0 || $value >= 100 || floor($value) === $value) ? 0 : 1;

        return NabuXUI::formatNumber($value, $decimals, $locale)."\u{2009}".$units[$unit];
    };

    $entries = array_values(array_map(fn ($item) => [
        'id' => (string) $item['id'],
        'name' => (string) $item['name'],
        'kind' => $kindOf($item),
        'parent' => isset($item['parent']) ? (string) $item['parent'] : null,
        'size' => isset($item['size']) ? (float) $item['size'] : null,
        'items' => isset($item['items']) ? (int) $item['items'] : null,
        'modified' => $when($item['modified'] ?? null),
        'href' => isset($item['href']) ? (string) $item['href'] : null,
        'preview' => isset($item['preview']) ? (string) $item['preview'] : null,
        'details' => array_values(array_map(fn ($detail) => [
            'label' => (string) $detail['label'],
            'value' => (string) $detail['value'],
        ], (array) ($item['details'] ?? []))),
    ], (array) $items));

    // Folders first inside every folder, names in the reader's collation.
    $coll = class_exists(\Collator::class) ? new \Collator($locale) : null;
    usort($entries, function ($a, $b) use ($coll) {
        if (($byParent = strcmp((string) $a['parent'], (string) $b['parent'])) !== 0) {
            return $byParent;
        }
        if (($byKind = ($a['kind'] === 'folder' ? 0 : 1) - ($b['kind'] === 'folder' ? 0 : 1)) !== 0) {
            return $byKind;
        }

        return $coll ? (int) $coll->compare($a['name'], $b['name']) : strcasecmp($a['name'], $b['name']);
    });

    $byId = collect($entries)->keyBy('id');
    $folders = array_values(array_filter($entries, fn ($entry) => $entry['kind'] === 'folder'));

    // The crumbs above the current folder, outermost first.
    $current = $current ? (string) $current : null;
    $trail = [];
    $at = $current;
    while ($at && ! in_array($at, $trail, true)) {
        $entry = $byId->get($at);
        if (! $entry) {
            break;
        }
        array_unshift($trail, $at);
        $at = $entry['parent'];
    }
    $activeCrumb = $current ?? '';

    $view = in_array($view, ['grid', 'list'], true) ? $view : 'grid';
    $visibleCount = count(array_filter($entries, fn ($entry) => (string) ($entry['parent'] ?? '') === (string) ($current ?? '')));
    $currentName = $current ? ($byId->get($current)['name'] ?? null) : null;

    $metaOf = function ($entry) use ($bytes, $labels, $locale) {
        $parts = [];
        if ($entry['kind'] === 'folder' && $entry['items'] !== null) {
            $parts[] = str_replace(':count', NabuXUI::formatNumber($entry['items'], 0, $locale), $labels['items']);
        }
        if ($entry['kind'] !== 'folder' && $entry['size'] !== null) {
            $parts[] = $bytes($entry['size']);
        }
        if ($entry['modified'] !== null) {
            $parts[] = $entry['modified'];
        }

        return implode(' · ', $parts);
    };

    $id = NabuXUI::id('nx-fm');
    // A wire:model on the component belongs to the picker (Livewire uploads); listeners stay on the root.
    $uploadAttrs = $attributes->whereStartsWith('wire:model');
    $wireUpload = $uploadAttrs->isNotEmpty();
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:'])->whereDoesntStartWith('wire:model');
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<section {{ $rest->class('nx-file-manager')->merge([
    'aria-label' => $labels['board'],
    'data-nx-reveal' => '',
    'style' => $height ? "--nx-fm-height: {$height}" : null,
]) }} {{ $listenerAttrs }} x-on:keydown.escape="close()"
    x-data="nxFileManager(@js(['items' => $entries, 'labels' => $labels, 'locale' => $locale, 'view' => $view, 'current' => $current, 'wireUpload' => $wireUpload]))">
    <header class="nx-fm-toolbar">
        <nav class="nx-fm-crumbs" aria-label="{{ $labels['breadcrumb'] }}">
            <button type="button" class="nx-fm-crumb" data-folder="" id="{{ $id }}-crumb-root" wire:key="{{ $id }}-crumb-root"
                @if ($activeCrumb === '') aria-current="page" @endif
                x-on:click="navigate(null)">
                {{ NabuXUI::icon('home') }}<span>{{ $labels['board'] }}</span>
            </button>
            @foreach ($folders as $folder)
                <button type="button" class="nx-fm-crumb" data-folder="{{ $folder['id'] }}" id="{{ $id }}-crumb-{{ $folder['id'] }}"
                    wire:key="{{ $id }}-crumb-{{ $folder['id'] }}"
                    @if (! in_array($folder['id'], $trail, true)) hidden @endif
                    @if ($folder['id'] === $activeCrumb) aria-current="page" @endif
                    x-on:click="navigate(@js($folder['id']))">
                    <span>{{ $folder['name'] }}</span>
                </button>
            @endforeach
        </nav>
        <span class="nx-fm-countwrap" wire:ignore>
            <span class="nx-fm-count" aria-hidden="true">
                <x-nx::number :value="$visibleCount" :reveal="false" :locale="$locale" />
            </span>
            {{-- The spoken count is server-rendered so it reads without JS; Alpine keeps it honest. --}}
            <span class="nx-visually-hidden" x-text="countText()">{{ str_replace(':count', NabuXUI::formatNumber($visibleCount, 0, $locale), $labels['items']) }}</span>
        </span>
        <div class="nx-fm-tools">
            <div class="nx-fm-search" x-bind:data-query="query ? '' : null">
                {{ NabuXUI::icon('search') }}
                <input class="nx-fm-search-input" type="search" x-ref="search" x-model="query"
                    aria-label="{{ $labels['search'] }}" placeholder="{{ $labels['searchPlaceholder'] }}" />
                <button type="button" class="nx-fm-clear" aria-label="{{ $labels['clearSearch'] }}" hidden
                    x-bind:hidden="query ? null : ''" x-on:click="clearSearch()">
                    {{ NabuXUI::icon('x') }}
                </button>
            </div>
            <x-nx::segmented class="nx-fm-view" size="sm" :label="$labels['view']" :value="$view"
                :options="['grid' => ['label' => $labels['grid'], 'icon' => 'grid'], 'list' => ['label' => $labels['list'], 'icon' => 'menu']]"
                x-on:change="viewChange($event)" />
            <button type="button" class="nx-fm-upload" x-on:click="pick()">
                {{ NabuXUI::icon('upload') }}<span>{{ $labels['upload'] }}</span>
            </button>
            <input type="file" multiple class="nx-visually-hidden" tabindex="-1" aria-hidden="true" x-ref="picker"
                x-on:change="picked($event)" {{ $uploadAttrs }} />
        </div>
    </header>

    <div class="nx-fm-stage">
        <div class="nx-fm-scroll">
            <ul class="nx-fm-items" data-view="{{ $view }}" x-bind:data-view="view"
                aria-label="{{ $currentName ?? $labels['board'] }}" wire:key="{{ $id }}-items">
                @foreach ($entries as $i => $entry)
                    <li class="nx-fm-item" data-kind="{{ $entry['kind'] }}" data-id="{{ $entry['id'] }}"
                        data-parent="{{ $entry['parent'] ?? '' }}" data-name="{{ $entry['name'] }}"
                        wire:key="{{ $id }}-item-{{ $entry['id'] }}" style="--nx-i: {{ $i }}"
                        @if ((string) ($entry['parent'] ?? '') !== (string) ($current ?? '')) hidden @endif>
                        <button type="button" class="nx-fm-card"
                            x-bind:data-active="selected === @js($entry['id']) ? '' : null"
                            @if ($entry['kind'] !== 'folder')
                                x-bind:aria-pressed="selected === @js($entry['id']) ? 'true' : 'false'"
                            @endif
                            x-on:click="activate(@js($entry['id']))">
                            <span class="nx-fm-icon" data-kind="{{ $entry['kind'] }}" aria-hidden="true">
                                {{ NabuXUI::icon($kindIcons[$entry['kind']]) }}
                            </span>
                            <span class="nx-fm-name">{{ $entry['name'] }}</span>
                            @if (($meta = $metaOf($entry)) !== '')
                                <span class="nx-fm-meta">{{ $meta }}</span>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="nx-fm-empty" data-variant="folder" @if ($visibleCount > 0) hidden @endif wire:key="{{ $id }}-empty">
                {{ $labels['empty'] }}
            </div>
            <div class="nx-fm-empty" data-variant="results" hidden wire:key="{{ $id }}-noresults">
                {{ $labels['results'] }}
            </div>
        </div>
        <div class="nx-fm-scrim" aria-hidden="true" x-bind:data-open="selected ? '' : null" x-on:click="close()"></div>
        <aside class="nx-fm-drawer" role="region" aria-label="{{ $labels['details'] }}" wire:ignore
            x-bind:data-open="selected ? '' : null">
            <header class="nx-fm-drawer-head">
                <span class="nx-fm-icon nx-fm-drawer-icon" data-kind="file" x-bind:data-kind="selectedKind()" aria-hidden="true">
                    {{ NabuXUI::icon('file') }}
                </span>
                <div class="nx-fm-drawer-id">
                    <h3 class="nx-fm-drawer-title" x-text="selected ? entryName(selected) : ''"></h3>
                    <p class="nx-fm-drawer-kind" x-text="selected ? entryKindLabel(selected) : ''"></p>
                </div>
                <button type="button" class="nx-fm-drawer-close" aria-label="{{ $labels['close'] }}" x-on:click="close()">
                    {{ NabuXUI::icon('x') }}
                </button>
            </header>
            <div class="nx-fm-drawer-body">
                <p class="nx-fm-drawer-preview" hidden><img src="" alt="" /></p>
                <dl class="nx-fm-drawer-rows"></dl>
                <a class="nx-fm-drawer-open" hidden href="#">
                    {{ NabuXUI::icon('external-link') }}<span>{{ $labels['open'] }}</span>
                </a>
            </div>
        </aside>
    </div>
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
</section>
