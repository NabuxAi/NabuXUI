{{--
    <x-nx::app-window platform="macos" title="Music" subtitle="Now playing" icon="music" active="library"
            brand="Nabu" :nav="[['id' => 'library', 'label' => 'Library', 'icon' => 'music'], …]"
            :metrics="[['value' => '128', 'label' => 'Tracks']]" height="34rem" statusbar="3 items">
        <x-slot:toolbar><x-nx::button size="sm">…</x-nx::button></x-slot:toolbar>
        … the app's content …
    </x-nx::app-window>

    One app window, four platform skins. `platform` (windows | macos | android |
    ios) picks the shell through the data-platform attribute — desktop platforms
    show the sidebar + caption, mobile ones the status strip (9:41 + signal +
    battery, with the iOS notch), the tab bar (tabs defaults to nav), the home
    indicator and, on android, the FAB when `fab` is set. The whole DOM is
    always rendered and the block CSS shows each part only where it belongs, so
    the skin can be swapped live: nxAppWindow reflects its `platform` state onto
    data-platform ($watch), and an outside change of the attribute (an Alpine
    bind) is picked up by the core behaviour, which re-syncs state and fires
    `nx-platform-change`. wire:model on the root binds the current nav item's
    id (x-modelable) — platform itself is a prop, not a model.

    The metrics/statusbar/fab props build their parts from plain values; the
    slots of the same names replace them (the slot is an Illuminate slot
    object, which is how the two are told apart despite sharing a name).

    Behaviour (core platformWindow): minimize parks the window in
    data-state=minimized and auto-restores, maximize toggles rest/maximized
    (hold it to open the Windows snap-layouts menu when snap), close fires
    `nx-window-close` and auto-restores after 0.8s — the auto-restore is for
    demos; pass snap=false or handle the events when embedding for real.
    Slots: titlebar, toolbar, sidebar, metrics, statusbar, fab — each replaces
    the matching built-in built from the props above.
--}}
@props([
    'platform' => 'windows',
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'nav' => [],
    'active' => null,
    'brand' => null,
    'brandMark' => null,
    'metrics' => [],
    'tabs' => null,
    'height' => null,
    'radius' => null,
    'state' => 'rest',
    'snap' => true,
    'fab' => null,
    'navLabel' => null,
    'statusbar' => null,
])
@php
    use NabuXUI\NabuXUI;
    use Illuminate\View\ComponentSlot;

    $lang = substr(app()->getLocale(), 0, 2);
    $say = fn (string $en, string $faText) => $lang === 'fa' ? $faText : $en;
    $shapeWords = [
        'half' => ['fa' => 'نیمه', 'en' => 'Half'],
        'third' => ['fa' => 'یک‌سوم', 'en' => 'Third'],
        'big' => ['fa' => 'بزرگ', 'en' => 'Large'],
        'quarter' => ['fa' => 'ربع', 'en' => 'Quarter'],
        'wide' => ['fa' => 'پهن', 'en' => 'Wide'],
        'mid' => ['fa' => 'میانه', 'en' => 'Middle'],
    ];
    $shapeWord = fn (string $shape) => $say($shapeWords[$shape]['en'] ?? $shape, $shapeWords[$shape]['fa'] ?? $shape);

    // The six Windows snap layouts; each zone carries the shape the window takes.
    $snapLayouts = [
        'halves' => ['half', 'half'],
        'thirds' => ['third', 'third', 'third'],
        'big-start' => ['big', 'third', 'third'],
        'quads' => ['quarter', 'quarter', 'quarter', 'quarter'],
        'big-top' => ['wide', 'half', 'half'],
        'mixed' => ['quarter', 'mid', 'quarter'],
    ];

    $platform = in_array($platform, ['windows', 'macos', 'android', 'ios'], true) ? $platform : 'windows';
    $state = in_array($state, ['rest', 'minimized', 'maximized'], true) ? $state : 'rest';
    $active = (string) ($active ?? ($nav[0]['id'] ?? ''));
    $tabs = $tabs ?? $nav;
    $snapId = NabuXUI::id('nx-app-snap');
    $mark = $brandMark ?? (is_string($brand) && $brand !== '' ? mb_substr($brand, 0, 1) : 'N');
    $style = implode('; ', array_filter([
        $height ? "--nx-app-height: {$height}" : null,
        $radius ? "--_radius: {$radius}" : null,
    ]));
    $maximizeLabel = $state === 'maximized' ? __('nabuxui::ui.restore') : __('nabuxui::ui.maximize');
    $metricsSlot = $metrics instanceof ComponentSlot ? $metrics : null;
    $metricsRows = is_array($metrics) ? $metrics : [];
    $statusbarSlot = $statusbar instanceof ComponentSlot ? $statusbar : null;
    $statusbarText = is_string($statusbar) && $statusbar !== '' ? $statusbar : null;
    $fabSlot = $fab instanceof ComponentSlot ? $fab : null;
    $fabLabel = is_string($fab) && $fab !== '' ? $fab : null;
@endphp
<div {{ $attributes->class('nx-app-window') }} data-platform="{{ $platform }}" data-state="{{ $state }}"
    x-data="nxAppWindow(@js($platform), @js($active), @js((bool) $snap))" x-modelable="active"
    @if ($style) style="{{ $style }}" @endif>
    @php
        // The phone chrome (mobile shells only, painted by the block CSS): the
        // 9:41 status strip above the screen and the home indicator below the
        // tab bar. Pure decoration — aria-hidden, no interactive parts.
    @endphp
    <div class="nx-app-status" aria-hidden="true">
        <span class="nx-app-status-time">9:41</span>
        <span class="nx-app-status-icons">
            <span class="nx-app-status-signal"></span>
            <span class="nx-app-status-battery"></span>
        </span>
    </div>
    <div class="nx-app-frame">
        <aside class="nx-app-sidebar">
            @isset($sidebar)
                {{ $sidebar }}
            @else
                <div class="nx-app-brand">
                    <span class="nx-app-mark" aria-hidden="true">{{ $mark }}</span>
                    @if ($brand)<span class="nx-app-brand-text">{{ $brand }}</span>@endif
                </div>
                <nav class="nx-app-nav" @if ($navLabel) aria-label="{{ $navLabel }}" @endif>
                    <span class="nx-indicator" aria-hidden="true"></span>
                    <ul>
                        @foreach ($nav as $item)
                            @php
                                $itemId = (string) $item['id'];
                                $current = $itemId === $active;
                            @endphp
                            <li>
                                @if (! empty($item['href']))
                                    <a href="{{ $item['href'] }}" class="nx-app-item" data-value="{{ $itemId }}" data-label="{{ $item['label'] }}" @if (! empty($item['navigate'])) wire:navigate @endif
                                        @if ($current) aria-current="page" @endif x-bind:aria-current="active === @js($itemId) ? 'page' : null" x-on:click="active = @js($itemId)">
                                @else
                                    <button type="button" class="nx-app-item" data-value="{{ $itemId }}" data-label="{{ $item['label'] }}"
                                        @if ($current) aria-current="page" @endif x-bind:aria-current="active === @js($itemId) ? 'page' : null" x-on:click="select(@js($itemId))">
                                @endif
                                    {{ NabuXUI::icon($item['icon'] ?? 'grid') }}
                                    <span class="nx-app-label">{{ $item['label'] }}</span>
                                    @if (isset($item['badge']))<span class="nx-app-badge">{{ $item['badge'] }}</span>@endif
                                @if (! empty($item['href']))</a>@else</button>@endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
                <div class="nx-app-sidebar-foot"></div>
            @endisset
        </aside>
        <div class="nx-app-main">
            @isset($titlebar)
                {{ $titlebar }}
            @else
                <header class="nx-app-titlebar">
                    @if ($icon)<span class="nx-app-title-icon" aria-hidden="true">{{ NabuXUI::icon($icon) }}</span>@endif
                    <div class="nx-app-heading">
                        @if ($title)<h2 class="nx-app-title">{{ $title }}</h2>@endif
                        @if ($subtitle)<p class="nx-app-subtitle">{{ $subtitle }}</p>@endif
                    </div>
                    @isset($toolbar)
                        <div class="nx-app-toolbar">{{ $toolbar }}</div>
                    @endif
                    <div class="nx-app-caption">
                        <button type="button" class="nx-app-caption-btn" data-kind="minimize" aria-label="{{ __('nabuxui::ui.minimize') }}"></button>
                            <div class="nx-app-snap">
                                <button type="button" class="nx-app-caption-btn" data-kind="maximize" aria-label="{{ $maximizeLabel }}"
                                    data-label-maximize="{{ __('nabuxui::ui.maximize') }}" data-label-restore="{{ __('nabuxui::ui.restore') }}"></button>
                            @if ((bool) $snap)
                                <div class="nx-app-snap-menu" id="{{ $snapId }}" popover="auto" role="menu" aria-label="{{ __('nabuxui::ui.snapLayouts') }}">
                                    @foreach ($snapLayouts as $zones => $shapesInLayout)
                                        <div class="nx-app-snap-layout" data-zones="{{ $zones }}" role="group" aria-label="{{ ucfirst($zones) }}">
                                            @foreach ($shapesInLayout as $index => $shape)
                                                <button type="button" role="menuitem" data-shape="{{ $shape }}"
                                                    aria-label="{{ $shapeWord($shape) }} — {{ $index + 1 }}"></button>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <button type="button" class="nx-app-caption-btn" data-kind="close" aria-label="{{ __('nabuxui::ui.close') }}"></button>
                    </div>
                </header>
            @endisset
            <div class="nx-app-content">
                @if ($metricsSlot)
                    {{ $metricsSlot }}
                @elseif (count($metricsRows) > 0)
                    <dl class="nx-app-metrics">
                        @foreach ($metricsRows as $metric)
                            @php
                                $delta = $metric['delta'] ?? null;
                                $trend = $metric['trend'] ?? (str_starts_with((string) $delta, '+') ? 'up' : (str_starts_with((string) $delta, '-') ? 'down' : null));
                            @endphp
                            <div class="nx-app-metric">
                                <dt class="nx-app-metric-value">{{ $metric['value'] }}</dt>
                                <dd class="nx-app-metric-label">{{ $metric['label'] }}</dd>
                                @if ($delta !== null)<dd class="nx-app-metric-delta" @if ($trend) data-trend="{{ $trend }}" @endif>{{ $delta }}</dd>@endif
                            </div>
                        @endforeach
                    </dl>
                @endif
                {{ $slot }}
            </div>
            @if ($statusbarSlot || $statusbarText)
                <footer class="nx-app-statusbar">
                    @if ($statusbarSlot){{ $statusbarSlot }}@else{{ $statusbarText }}@endif
                </footer>
            @endif
        </div>
    </div>
    <nav class="nx-app-tabbar" @if ($navLabel) aria-label="{{ $navLabel }}" @endif>
        @foreach ($tabs as $tab)
            @php
                $tabId = (string) $tab['id'];
                $tabCurrent = $tabId === $active;
            @endphp
            @if (! empty($tab['href']))
                <a href="{{ $tab['href'] }}" class="nx-app-tab" data-value="{{ $tabId }}" @if (! empty($tab['navigate'])) wire:navigate @endif
                    @if ($tabCurrent) aria-current="page" @endif x-bind:aria-current="active === @js($tabId) ? 'page' : null" x-on:click="active = @js($tabId)">
            @else
                <button type="button" class="nx-app-tab" data-value="{{ $tabId }}"
                    @if ($tabCurrent) aria-current="page" @endif x-bind:aria-current="active === @js($tabId) ? 'page' : null" x-on:click="select(@js($tabId))">
            @endif
                {{ NabuXUI::icon($tab['icon'] ?? 'grid') }}
                <span class="nx-app-tab-label">{{ $tab['label'] }}</span>
            @if (! empty($tab['href']))</a>@else</button>@endif
        @endforeach
    </nav>
    <span class="nx-app-homebar" aria-hidden="true"></span>
    @if ($fabSlot || $fabLabel)
        <button type="button" class="nx-app-fab" aria-label="{{ $fabLabel ?? __('nabuxui::ui.plus') }}">
            @if ($fabSlot){{ $fabSlot }}@else{{ NabuXUI::icon('plus') }}@endif
        </button>
    @endif
</div>
