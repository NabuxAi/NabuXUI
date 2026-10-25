{{--
    The music app across four platform shells — same shape as the notebook
    page: one <x-nx::app-window> per skin in the 2×2 grid, all framing
    apps/music-body.blade.php (album grid + playback bar). This page also
    exercises the `metrics` prop (the dl.nx-app-metrics strip above the
    content) and a playback statusbar; the switcher above re-skins all
    four windows live through `uniform`.
--}}
@php
    use NabuXUI\NabuXUI;

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
        ['id' => 'library', 'label' => $say('Library', 'کتابخانه'), 'icon' => 'music'],
        ['id' => 'search', 'label' => $say('Search', 'جست‌وجو'), 'icon' => 'search'],
        ['id' => 'favorites', 'label' => $say('Favorites', 'علاقه‌مندی‌ها'), 'icon' => 'heart'],
    ];
    $title = $say('Music', 'موسیقی');
    $subtitle = $say('Sea of Glass · playing', 'دریای شیشه · در حال پخش');
    $metrics = [
        ['value' => NabuXUI::formatNumber(128), 'label' => $say('Tracks', 'آهنگ')],
        ['value' => NabuXUI::formatNumber(12), 'label' => $say('Albums', 'آلبوم'), 'delta' => '+'.NabuXUI::formatNumber(2), 'trend' => 'up'],
        ['value' => NabuXUI::formatNumber(4), 'label' => $say('Playlists', 'پلی‌لیست')],
    ];
@endphp
<section class="pg-box" style="gap: 1.25rem" x-data="{ uniform: null, names: @js($names) }">
    @include('demos.components.platform-apps._switcher')
    <div class="pa-grid">
        @foreach ($skins as $skin)
            <figure class="pa-cell">
                <figcaption class="pa-tag" x-text="names[uniform ?? @js($skin['id'])]"></figcaption>
                <x-nx::app-window platform="{{ $skin['id'] }}" :x-bind:data-platform="$binds[$skin['id']]"
                    icon="music" :title="$title" :subtitle="$subtitle" brand="Nabu" :nav="$nav" active="library"
                    :metrics="$metrics" height="34rem" :fab="$say('Play', 'پخش')"
                    :statusbar="$say('1:42 of 4:26 · Sea of Glass', '۱:۴۲ از ۴:۲۶ · دریای شیشه')">
                    @include('demos.components.platform-apps.apps.music-body')
                </x-nx::app-window>
            </figure>
        @endforeach
    </div>
</section>
