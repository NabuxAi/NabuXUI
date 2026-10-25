{{--
    The settings app across four platform shells — same shape as the
    notebook page: one <x-nx::app-window> per skin in the 2×2 grid, all
    framing apps/settings-body.blade.php (in-window section rail + native
    switch/select rows). This page turns the FAB on (visible wherever the
    Android shell is showing, whichever window that is after a `uniform`
    re-skin) and keeps snap on everywhere, so holding the Windows maximize
    button opens the snap-layouts flyout while the mobile skins show no
    caption bar at all.
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
        ['id' => 'general', 'label' => $say('General', 'عمومی'), 'icon' => 'settings'],
        ['id' => 'alerts', 'label' => $say('Alerts', 'اعلان‌ها'), 'icon' => 'bell'],
        ['id' => 'storage', 'label' => $say('Storage', 'ذخیره‌سازی'), 'icon' => 'folder'],
    ];
    $title = $say('Settings', 'تنظیمات');
    $subtitle = $say('Nabu Notes · v1.0', 'نابونوتز · نسخهٔ ۱٫۰');
@endphp
<section class="pg-box" style="gap: 1.25rem" x-data="{ uniform: null, names: @js($names) }">
    @include('demos.components.platform-apps._switcher')
    <div class="pa-grid">
        @foreach ($skins as $skin)
            <figure class="pa-cell">
                <figcaption class="pa-tag" x-text="names[uniform ?? @js($skin['id'])]"></figcaption>
                <x-nx::app-window platform="{{ $skin['id'] }}" :x-bind:data-platform="$binds[$skin['id']]"
                    icon="settings" :title="$title" :subtitle="$subtitle" brand="Nabu" :nav="$nav" active="general"
                    height="32rem" :fab="$say('Add account', 'افزودن حساب')" :statusbar="$say('Everything up to date', 'همه‌چیز به‌روز است')">
                    @include('demos.components.platform-apps.apps.settings-body')
                </x-nx::app-window>
            </figure>
        @endforeach
    </div>
</section>
