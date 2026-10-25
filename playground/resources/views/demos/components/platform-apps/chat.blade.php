{{--
    The chat app across four platform shells — same shape as the notebook
    page: one <x-nx::app-window> per skin in the 2×2 grid, all framing
    apps/chat-body.blade.php (conversation list + message thread, light and
    static, no nx-chat dependency). The nav carries an unread badge — it
    rides along into the mobile tab bar, where tabs default to nav; the
    statusbar counts what is still unread. `uniform` re-skins all four
    windows live.
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
        ['id' => 'chats', 'label' => $say('Chats', 'گفت‌وگوها'), 'icon' => 'message', 'badge' => 3],
        ['id' => 'people', 'label' => $say('People', 'افراد'), 'icon' => 'users'],
        ['id' => 'calls', 'label' => $say('Calls', 'تماس‌ها'), 'icon' => 'mic'],
    ];
    $title = $say('Chat', 'گفت‌وگو');
    $subtitle = $say('Sara · typing', 'سارا · در حال نوشتن');
@endphp
<section class="pg-box" style="gap: 1.25rem" x-data="{ uniform: null, names: @js($names) }">
    @include('demos.components.platform-apps._switcher')
    <div class="pa-grid">
        @foreach ($skins as $skin)
            <figure class="pa-cell">
                <figcaption class="pa-tag" x-text="names[uniform ?? @js($skin['id'])]"></figcaption>
                <x-nx::app-window platform="{{ $skin['id'] }}" :x-bind:data-platform="$binds[$skin['id']]"
                    icon="message" :title="$title" :subtitle="$subtitle" brand="Nabu" :nav="$nav" active="chats"
                    height="36rem" :fab="$say('New chat', 'گفت‌وگوی تازه')"
                    :statusbar="$say('3 unread conversations', '۳ گفت‌وگوی خوانده‌نشده')">
                    @include('demos.components.platform-apps.apps.chat-body')
                </x-nx::app-window>
            </figure>
        @endforeach
    </div>
</section>
