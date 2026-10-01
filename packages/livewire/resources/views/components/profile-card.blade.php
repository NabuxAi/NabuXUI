{{--
    <x-nx::profile-card name="نگار رستمی" role="طراح ارشد محصول" handle="@negar" href="#"
        status="online" verified :avatar="['src' => '…']"
        :cover="['src' => '…', 'alt' => '']"
        :stats="[
            ['label' => 'دنبال‌کننده', 'value' => 12840],
            ['label' => 'دنبال‌شده', 'value' => 812],
            ['label' => 'پروژه', 'value' => 46],
        ]" :followersStat="0"
        :tabs="['about' => 'درباره', 'activity' => 'فعالیت']">
        <x-slot:about>…</x-slot:about>
        <x-slot:activity>…</x-slot:activity>
    </x-nx::profile-card>

    A profile over a gradient cover: the avatar rides the cover/body seam (with an
    optional presence dot), the stats roll their digits, the follow button morphs
    between “follow” and “following” (icon swap + label crossfade + recolour), the
    message button is a link, a wire method or a plain nx-message event, and the
    small tabs pass a springing indicator over the chosen section.

    State: `following` is the truth — re-rendered from outside, or flipped locally
    (the optimistic morph). `follow-action` / `unfollow-action` / `message-action`
    name Livewire methods called after the optimistic flip; without them
    nx-follow / nx-unfollow / nx-message events bubble for you to handle.
    `followers-stat` is the index of the stat that counts followers; it rolls ±1
    with the button until the server's own number arrives.
--}}
@props([
    'name',
    'href' => null,
    'role' => null,
    'handle' => null,
    'avatar' => null,
    'status' => null,
    'verified' => false,
    'cover' => null,
    'stats' => [],
    'followersStat' => null,
    'following' => false,
    'followAction' => null,
    'unfollowAction' => null,
    'messageHref' => null,
    'messageAction' => null,
    'tabs' => [],
    'tab' => null,
    'labels' => [],
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The card's own words live in the core i18n table (resources/lang, generated
    // from it with :name placeholders); `labels` overrides may use {name} or :name.
    $say = function (string $key, array $params = []) use ($lang, $labels) {
        $text = (string) ($labels[$key] ?? __('nabuxui::ui.'.$key, [], $lang));
        foreach ($params as $param => $value) {
            $text = str_replace([":{$param}", "{{$param}}"], (string) $value, $text);
        }

        return $text;
    };

    $status = in_array($status, ['online', 'busy', 'away'], true) ? $status : null;
    $verified = filter_var($verified, FILTER_VALIDATE_BOOLEAN);
    $following = filter_var($following, FILTER_VALIDATE_BOOLEAN);

    $avatar = is_array($avatar) ? [
        'src' => (string) ($avatar['src'] ?? ''),
        'alt' => (string) ($avatar['alt'] ?? ''),
    ] : null;
    $cover = is_array($cover) ? [
        'src' => (string) ($cover['src'] ?? ''),
        'alt' => (string) ($cover['alt'] ?? ''),
    ] : null;

    $stats = array_values(array_map(fn ($stat) => [
        'label' => (string) ($stat['label'] ?? ''),
        'value' => (float) ($stat['value'] ?? 0),
    ], (array) $stats));
    $followersStat = $followersStat !== null && array_key_exists((int) $followersStat, $stats) ? (int) $followersStat : null;

    // Tabs: ['about' => 'About'] or ['about' => ['label' => 'About']]; the panel of
    // each tab is its named slot (<x-slot:about>…), like <x-nx::tabs>.
    $tabs = array_map(fn ($tab) => is_array($tab) ? $tab : ['label' => $tab], (array) $tabs);
    $tabKeys = array_keys($tabs);
    $tab = (string) ($tab ?? ($tabKeys[0] ?? ''));
    if (! array_key_exists($tab, $tabs)) {
        $tab = (string) ($tabKeys[0] ?? '');
    }
    $panels = $__laravel_slots ?? [];

    $initials = mb_strtoupper(implode('', array_map(fn ($part) => mb_substr($part, 0, 1), array_slice(preg_split('/\s+/u', trim((string) $name)), 0, 2))));

    $id = NabuXUI::id('nx-profile-card');
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<article {{ $rest->class('nx-profile-card')->merge([
    'data-covered' => $cover ? '' : null,
    'data-nx-reveal' => 'group',
]) }} {{ $listenerAttrs }} aria-labelledby="{{ $id }}-name"
    x-data="nxProfileCard(@js([
        'following' => $following,
        'tab' => $tab,
        'followAction' => $followAction,
        'unfollowAction' => $unfollowAction,
        'messageAction' => $messageAction,
        'name' => is_string($name) ? $name : null,
        'followersStat' => $followersStat,
        'stats' => array_column($stats, 'value'),
        'locale' => $locale,
        'labels' => [
            'nowFollowing' => $say('profileNowFollowing', ['name' => $name]),
            'unfollowed' => $say('profileUnfollowed', ['name' => $name]),
        ],
    ]))" x-nx-reveal.group>
    <div class="nx-profile-card-cover">
        @if ($cover)
            <img class="nx-profile-card-cover-img" src="{{ $cover['src'] }}" alt="{{ $cover['alt'] }}" loading="lazy" />
        @endif
    </div>

    <div class="nx-profile-card-body">
        <div class="nx-profile-card-id">
            <span class="nx-profile-card-avatar" role="img" aria-label="{{ $name }}"
                @if ($status) data-status="{{ $status }}" @endif
                @if ($avatar) x-data="{ failed: false }" @endif>
                @if ($avatar)
                    <img class="nx-profile-card-avatar-img" src="{{ $avatar['src'] }}" alt="" x-show="! failed" x-on:error="failed = true">
                    <span aria-hidden="true" x-show="failed" x-cloak>{{ $initials }}</span>
                @else
                    <span aria-hidden="true">{{ $initials }}</span>
                @endif
            </span>
            <div class="nx-profile-card-id-text">
                <h3 class="nx-profile-card-name" id="{{ $id }}-name">
                    @if ($href)
                        <a class="nx-profile-card-link" href="{{ $href }}">{{ $name }}</a>
                    @else
                        {{ $name }}
                    @endif
                    @if ($verified)
                        <span class="nx-profile-card-verified">
                            {{ NabuXUI::icon('check-circle') }}
                            <span class="nx-visually-hidden">{{ $say('profileVerified') }}</span>
                        </span>
                    @endif
                </h3>
                @if ($role || $handle)
                    <p class="nx-profile-card-role">
                        @if ($role)<span>{{ $role }}</span>@endif
                        @if ($role && $handle)<span aria-hidden="true">·</span>@endif
                        @if ($handle)<span class="nx-profile-card-handle">{{ $handle }}</span>@endif
                    </p>
                @endif
            </div>
        </div>

        <div class="nx-profile-card-actions">
            <button type="button" class="nx-profile-card-follow" wire:key="{{ $id }}-follow"
                @if ($following) data-following @endif
                x-bind:data-following="following ? '' : null"
                x-bind:disabled="busy"
                x-on:click="toggle()">
                <span class="nx-profile-card-follow-icon" aria-hidden="true">
                    <span data-part="idle">{{ NabuXUI::icon('plus') }}</span>
                    <span data-part="done">{{ NabuXUI::icon('check') }}</span>
                </span>
                {{-- Both labels live on one grid cell; the resting one is opacity-0, so it must also leave the accessibility tree. --}}
                <span class="nx-profile-card-follow-label">
                    <span data-part="idle" @if ($following) aria-hidden="true" @endif
                        x-bind:aria-hidden="following ? 'true' : null">{{ $say('profileFollow') }}</span>
                    <span data-part="done" @if (! $following) aria-hidden="true" @endif
                        x-bind:aria-hidden="following ? null : 'true'">{{ $say('profileFollowing') }}</span>
                </span>
            </button>
            @if ($messageHref)
                <a class="nx-profile-card-message" href="{{ $messageHref }}">
                    {{ NabuXUI::icon('message') }}<span>{{ $say('profileMessage') }}</span>
                </a>
            @else
                <button type="button" class="nx-profile-card-message" wire:key="{{ $id }}-message"
                    x-on:click="message()">
                    {{ NabuXUI::icon('message') }}<span>{{ $say('profileMessage') }}</span>
                </button>
            @endif
        </div>

        @if (count($stats) > 0)
            <dl class="nx-profile-card-stats">
                @foreach ($stats as $i => $stat)
                    <div class="nx-profile-card-stat" data-stat="{{ $i }}" wire:key="{{ $id }}-stat-{{ $i }}">
                        <dt>{{ $stat['label'] }}</dt>
                        <dd><x-nx::number :value="$stat['value']" :locale="$locale" /></dd>
                    </div>
                @endforeach
            </dl>
        @endif

        @if (count($tabs) > 0)
            <div class="nx-profile-card-tabs" role="tablist" x-ref="tabs" aria-label="{{ $say('profileSections') }}" x-on:keydown="tabKey($event)">
                <span class="nx-indicator" aria-hidden="true"></span>
                @foreach ($tabs as $key => $tabItem)
                    @php $key = (string) $key; @endphp
                    <button type="button" role="tab" class="nx-profile-card-tab" id="{{ $id }}-tab-{{ $key }}"
                        data-value="{{ $key }}" aria-controls="{{ $id }}-panel-{{ $key }}"
                        aria-selected="{{ $key === $tab ? 'true' : 'false' }}" tabindex="{{ $key === $tab ? 0 : -1 }}"
                        x-bind:aria-selected="tab === @js($key) ? 'true' : 'false'"
                        x-bind:tabindex="tab === @js($key) ? 0 : -1"
                        x-on:click="select(@js($key))">{{ $tabItem['label'] ?? '' }}</button>
                @endforeach
            </div>
            @foreach ($tabs as $key => $tabItem)
                @php $key = (string) $key; @endphp
                <div class="nx-profile-card-panel" role="tabpanel" id="{{ $id }}-panel-{{ $key }}"
                    aria-labelledby="{{ $id }}-tab-{{ $key }}" tabindex="0"
                    x-show="tab === @js($key)" @if ($key !== $tab) style="display: none" @endif>{{ $panels[$key] ?? '' }}</div>
            @endforeach
        @endif
    </div>
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
</article>
