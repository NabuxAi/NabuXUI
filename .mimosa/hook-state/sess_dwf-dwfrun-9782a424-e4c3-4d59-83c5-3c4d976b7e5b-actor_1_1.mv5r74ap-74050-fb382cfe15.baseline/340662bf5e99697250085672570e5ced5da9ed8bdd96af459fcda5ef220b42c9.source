{{--
    <x-nx::audio-room title="Design crit · 設計レビュー" :listeners="1284" :members="[
        ['name' => 'Kenji Sato', 'role' => 'Host', 'speaking' => true],
        ['name' => 'María López', 'muted' => true],
    ]" x-on:nx-room-leave="$wire.ping('Left the room')" />

    A live pill that opens like a dynamic island. Who is speaking and how many
    listen come from the render, so Livewire morphs them in (the count rolls).
    Events: nx-room-mute { muted }, nx-room-hand { raised }, nx-room-leave.
--}}
@props(['title', 'members' => [], 'listeners' => 0, 'open' => false, 'muted' => false, 'handRaised' => false, 'locale' => null])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-room');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $speaking = collect($members)->contains(fn ($member) => ! empty($member['speaking']));
    $glyph = fn (string $d, bool $directional = false) => new \Illuminate\Support\HtmlString('<svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"'.($directional ? ' data-directional' : '').'><path d="'.$d.'"/></svg>');
    // The same paths as the core's menuGlyphs.
    $micOff = 'M12 15a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zM19 11a7 7 0 0 1-14 0M12 18v3M4 4l16 16';
    $hand = 'M7 12.5V7.25a1.25 1.25 0 0 1 2.5 0v4.25M9.5 11.5V5.25a1.25 1.25 0 0 1 2.5 0v6.25M12 11.5V5.75a1.25 1.25 0 0 1 2.5 0v5.75M14.5 11.5V7.75a1.25 1.25 0 0 1 2.5 0V14a6 6 0 0 1-6 6h-.6a5 5 0 0 1-3.8-1.8l-2.7-3.3a1.3 1.3 0 0 1 2-1.6L7 14.5v-2';
    $handUp = $hand.'M3.6 8.2l1.6.5M4 4.6l1.3 1.1M20.4 8.2l-1.6.5M20 4.6l-1.3 1.1';
    $leave = 'M10 4H6.5A1.5 1.5 0 0 0 5 5.5v13A1.5 1.5 0 0 0 6.5 20H10M14.5 8l4 4-4 4M18.5 12H9.5';
    $words = [
        'mute' => $t('Mute', 'بی‌صدا کردن', 'كتم الصوت'),
        'unmute' => $t('Unmute', 'باصدا کردن', 'إلغاء الكتم'),
        'raise' => $t('Raise hand', 'بالا بردن دست', 'رفع اليد'),
        'lower' => $t('Lower hand', 'پایین آوردن دست', 'خفض اليد'),
    ];
@endphp
<div {{ $attributes->class('nx-audio-room') }} wire:ignore.self x-data="nxAudioRoom(@js((bool) $open), @js((bool) $muted), @js((bool) $handRaised))"
    data-state="{{ $open ? 'open' : 'closed' }}" x-bind:data-state="open ? 'open' : 'closed'">
    <div class="nx-audio-room-shell" x-ref="shell" wire:ignore.self>
        <div class="nx-audio-room-measure" x-ref="measure">
            <button type="button" class="nx-audio-room-pill" x-ref="pill" aria-controls="{{ $id }}-panel"
                aria-expanded="false" x-bind:aria-expanded="open ? 'true' : 'false'" x-bind:tabindex="open ? -1 : 0" x-on:click="open = true">
                <span class="nx-audio-room-live" aria-hidden="true"></span>
                <span class="nx-visually-hidden">{{ $t('Live', 'زنده', 'مباشر') }}:</span>
                <span class="nx-audio-room-title">{{ $title }}</span>
                <span class="nx-audio-room-faces" aria-hidden="true">
                    @foreach (array_slice($members, 0, 3) as $member)
                        <x-nx::avatar :name="$member['name']" :src="$member['avatar'] ?? null" size="xs" />
                    @endforeach
                </span>
                <span class="nx-audio-room-eq" @if ($speaking) data-active @endif aria-hidden="true"><i></i><i></i><i></i><i></i></span>
            </button>
            <section class="nx-audio-room-panel" id="{{ $id }}-panel" role="region" aria-label="{{ $title }}" aria-hidden="true" x-bind:aria-hidden="open ? null : 'true'">
                <div class="nx-audio-room-head">
                    <div class="nx-audio-room-heading">
                        <p class="nx-audio-room-name">{{ $title }}</p>
                        <div class="nx-audio-room-meta">
                            <span class="nx-audio-room-badge"><span class="nx-audio-room-live" aria-hidden="true"></span>{{ $t('Live', 'زنده', 'مباشر') }}</span>
                            <span class="nx-audio-room-count"><x-nx::number :value="$listeners" :locale="$locale" :reveal="false" /><span>{{ $t('listening', 'شنونده', 'يستمعون') }}</span></span>
                        </div>
                    </div>
                    <button type="button" class="nx-audio-room-close" x-ref="close" aria-label="{{ $t('Minimize', 'کوچک کردن', 'تصغير') }}" x-bind:tabindex="open ? 0 : -1" x-on:click="collapse()">{{ NabuXUI::icon('chevron-up') }}</button>
                </div>
                <ul class="nx-audio-room-speakers" aria-label="{{ $t('Speakers', 'سخنران‌ها', 'المتحدثون') }}">
                    @foreach ($members as $i => $member)
                        <li class="nx-audio-room-speaker" style="--nx-i: {{ $i }}" @if (! empty($member['speaking'])) data-speaking @endif>
                            <span class="nx-audio-room-avatar">
                                <x-nx::avatar :name="$member['name']" :src="$member['avatar'] ?? null" />
                                @if (! empty($member['muted']))<span class="nx-audio-room-mic" role="img" aria-label="{{ $t('Muted', 'بی‌صدا', 'مكتوم') }}">{{ $glyph($micOff) }}</span>@endif
                            </span>
                            <span class="nx-audio-room-person">{{ $member['name'] }}</span>
                            @if (! empty($member['role']))<span class="nx-audio-room-role">{{ $member['role'] }}</span>@endif
                            @if (! empty($member['speaking']))<span class="nx-visually-hidden">{{ $t('Speaking', 'در حال صحبت', 'يتحدث') }}</span>@endif
                        </li>
                    @endforeach
                </ul>
                <div class="nx-audio-room-controls">
                    <button type="button" class="nx-audio-room-control" aria-pressed="{{ $muted ? 'true' : 'false' }}" x-bind:aria-pressed="muted ? 'true' : 'false'" x-bind:tabindex="open ? 0 : -1" x-on:click="toggleMute()">
                        <span class="nx-audio-room-swap" aria-hidden="true">{{ NabuXUI::icon('mic') }}{{ $glyph($micOff) }}</span>
                        <span x-text="muted ? @js($words['unmute']) : @js($words['mute'])">{{ $muted ? $words['unmute'] : $words['mute'] }}</span>
                    </button>
                    <button type="button" class="nx-audio-room-control" aria-pressed="{{ $handRaised ? 'true' : 'false' }}" x-bind:aria-pressed="raised ? 'true' : 'false'" x-bind:tabindex="open ? 0 : -1" x-on:click="toggleHand()">
                        <span class="nx-audio-room-swap" aria-hidden="true">{{ $glyph($hand) }}{{ $glyph($handUp) }}</span>
                        <span x-text="raised ? @js($words['lower']) : @js($words['raise'])">{{ $handRaised ? $words['lower'] : $words['raise'] }}</span>
                    </button>
                    <button type="button" class="nx-audio-room-control" data-tone="danger" x-bind:tabindex="open ? 0 : -1" x-on:click="leave()">
                        {{ $glyph($leave, true) }}<span>{{ $t('Leave', 'خروج', 'مغادرة') }}</span>
                    </button>
                </div>
            </section>
        </div>
    </div>
</div>
