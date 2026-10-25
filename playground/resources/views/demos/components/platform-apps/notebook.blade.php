{{--
    The notes app across four platform shells — the canonical shape of a
    platform-apps page. One <x-nx::app-window> per skin (windows · macos ·
    android · ios) in a 2×2 grid, all framing the same shared body
    (apps/notebook-body.blade.php: notes list + editor). The page's Alpine
    state `uniform` is what the shared switcher (_switcher.blade.php)
    drives: null keeps each window on the platform prop it was given, a
    platform id re-skins all four in place through each root's
    x-bind:data-platform (the core behaviour's observer follows and fires
    nx-platform-change). The tag above each cell names the shell the
    window wears right now.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $skins = [
        ['id' => 'windows', 'label' => $say('Windows', 'ویندوز')],
        ['id' => 'macos', 'label' => $say('macOS', 'مک‌او‌اس')],
        ['id' => 'android', 'label' => $say('Android', 'اندروید')],
        ['id' => 'ios', 'label' => $say('iOS', 'آی‌او‌اس')],
    ];
    $names = array_column($skins, 'label', 'id');
    // Alpine expressions travel through the component tag as pre-built
    // strings: static tag attributes pass verbatim (Blade directives like
    // @js are not compiled there), so build them here in plain PHP.
    $binds = [];
    foreach ($skins as $skin) {
        $binds[$skin['id']] = "uniform ?? '{$skin['id']}'";
    }

    $nav = [
        ['id' => 'notes', 'label' => $say('Notes', 'یادداشت‌ها'), 'icon' => 'edit'],
        ['id' => 'starred', 'label' => $say('Starred', 'ستاره‌دار'), 'icon' => 'star', 'badge' => 2],
        ['id' => 'archive', 'label' => $say('Archive', 'بایگانی'), 'icon' => 'folder'],
    ];
    $title = $say('Notes', 'یادداشت‌ها');
    $subtitle = $say('Nabu Notes · this week', 'نابونوتز · این هفته');
@endphp
<section class="pg-box" style="gap: 1.25rem" x-data="{ uniform: null, names: @js($names) }">
    @include('demos.components.platform-apps._switcher')
    <div class="pa-grid">
        @foreach ($skins as $skin)
            <figure class="pa-cell">
                <figcaption class="pa-tag" x-text="names[uniform ?? @js($skin['id'])]"></figcaption>
                <x-nx::app-window platform="{{ $skin['id'] }}" :x-bind:data-platform="$binds[$skin['id']]"
                    icon="edit" :title="$title" :subtitle="$subtitle" brand="Nabu" :nav="$nav" active="notes"
                    height="32rem" :fab="$say('New note', 'یادداشت تازه')" :statusbar="$say('Autosaved', 'خودکار ذخیره شد')">
                    <x-slot:toolbar>
                        <x-nx::button size="sm" variant="ghost" icon="plus">{{ $say('New', 'تازه') }}</x-nx::button>
                    </x-slot:toolbar>
                    @include('demos.components.platform-apps.apps.notebook-body')
                </x-nx::app-window>
            </figure>
        @endforeach
    </div>
</section>
