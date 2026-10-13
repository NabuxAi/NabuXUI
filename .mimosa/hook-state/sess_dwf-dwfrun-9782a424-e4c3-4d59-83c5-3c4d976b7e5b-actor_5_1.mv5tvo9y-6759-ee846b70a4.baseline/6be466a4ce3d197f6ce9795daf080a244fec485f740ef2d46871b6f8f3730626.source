{{--
    <x-nx::activity-dropdown :items="[
        ['id' => 'a1', 'actor' => ['name' => 'Amara Okafor'], 'text' => 'commented on', 'target' => 'Q3 roadmap', 'time' => now()->subMinutes(3), 'unread' => true, 'href' => '#'],
        ['id' => 'a2', 'actor' => ['name' => 'Kenji Sato'], 'text' => 'shared a file', 'time' => 'Yesterday'],
    ]" x-on:nx-mark-all-read="$wire.markAllRead()" />

    A bell with an unread badge and a popover of activity. `time` may be a date
    (shown relative, in the app's language) or a label as it is. "Mark all as
    read" dims the dots at once and dispatches nx-mark-all-read for the server.
--}}
@props(['items' => [], 'title' => null, 'emptyText' => null, 'locale' => null])
@php
    use Illuminate\Support\Carbon;
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-activity');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $title ??= __('nabuxui::ui.notifications');
    $unread = collect($items)->filter(fn ($item) => ! empty($item['unread']))->count();
    $unreadWord = $t(':count unread', ':count خوانده‌نشده', ':count غير مقروءة');
    $when = function ($time) use ($lang): array {
        if ($time instanceof \DateTimeInterface) {
            $date = Carbon::instance($time);
        } elseif (is_int($time) || is_float($time)) {
            $date = Carbon::createFromTimestampMs((int) $time);
        } elseif (is_string($time) && preg_match('/\d{4}-\d{2}-\d{2}/', $time)) {
            $date = Carbon::parse($time);
        } else {
            return [(string) $time, null];
        }

        // Carbon translates the words but leaves the counter in latin digits;
        // map them through the locale's numbering system (identity for latin locales).
        return [strtr($date->locale($lang)->diffForHumans(), array_combine(range(0, 9), NabuXUI::digits($lang))), $date->toIso8601String()];
    };
    // Listeners go on the wrapper: the panel's events bubble through it, not the trigger.
    $listeners = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<div style="display: contents" {{ $listeners }} x-data="nxActivityDropdown(@js($locale))">
    <button type="button" {{ $rest->class('nx-activity-dropdown-trigger') }} x-ref="trigger" popovertarget="{{ $id }}" aria-controls="{{ $id }}"
        aria-expanded="false" x-bind:aria-expanded="open ? 'true' : 'false'"
        aria-label="{{ $unread ? $title.', '.str_replace(':count', (string) $unread, $unreadWord) : $title }}"
        x-bind:aria-label="unread ? @js($title) + ', ' + @js($unreadWord).replace(':count', new Intl.NumberFormat(@js($lang)).format(unread)) : @js($title)"
        x-on:animationend="ringEnded($event)">
        {{ NabuXUI::icon('bell') }}
        <span class="nx-activity-dropdown-badge" x-ref="badge" aria-hidden="true" @if (! $unread) data-empty @endif
            x-bind:data-empty="unread ? null : ''" x-on:animationend.stop="$event.animationName === 'nx-pop' && $el.removeAttribute('data-pop')">
            <x-nx::number x-ref="count" :value="$unread" :locale="$locale" :reveal="false" wire:ignore />
        </span>
    </button>
    <div class="nx-activity-dropdown" id="{{ $id }}" x-ref="panel" popover="auto" wire:ignore.self role="dialog" aria-labelledby="{{ $id }}-title">
        <header class="nx-activity-dropdown-head">
            <div>
                <h2 class="nx-activity-dropdown-title" id="{{ $id }}-title">{{ $title }}</h2>
                <span class="nx-activity-dropdown-unread" x-show="unread" @if (! $unread) style="display: none" @endif
                    x-text="@js($unreadWord).replace(':count', new Intl.NumberFormat(@js($lang)).format(unread))">{{ str_replace(':count', (string) $unread, $unreadWord) }}</span>
            </div>
            <button type="button" class="nx-activity-dropdown-mark" @disabled(! $unread) x-bind:disabled="! unread" x-on:click="markAll()">{{ $t('Mark all as read', 'همه خوانده شد', 'تعليم الكل كمقروء') }}</button>
        </header>
        @if (count($items))
            <ul class="nx-activity-dropdown-list">
                @foreach ($items as $i => $item)
                    @php
                        $itemId = (string) $item['id'];
                        $isUnread = ! empty($item['unread']);
                        [$label, $iso] = $when($item['time'] ?? '');
                    @endphp
                    <li class="nx-activity-dropdown-item" style="--nx-i: {{ $i }}" data-id="{{ $itemId }}" @if ($isUnread) data-server-unread data-unread @endif
                        x-bind:data-unread="isUnread(@js($itemId), @js($isUnread)) ? '' : null">
                        @if (! empty($item['href']))
                            <a class="nx-activity-dropdown-row" href="{{ $item['href'] }}" x-on:click="markRead(@js($itemId))">
                        @else
                            <div class="nx-activity-dropdown-row">
                        @endif
                            <x-nx::avatar :name="$item['actor']['name']" :src="$item['actor']['avatar'] ?? null" />
                            <p class="nx-activity-dropdown-text">
                                <span><strong>{{ $item['actor']['name'] }}</strong> {{ $item['text'] }}@if (! empty($item['target'])) <strong>{{ $item['target'] }}</strong>@endif</span>
                                <time class="nx-activity-dropdown-time" @if ($iso) datetime="{{ $iso }}" @endif>{{ $label }}</time>
                            </p>
                            <span class="nx-activity-dropdown-dot" aria-hidden="true"></span>
                            @if ($isUnread)<span class="nx-visually-hidden" x-show="isUnread(@js($itemId), true)">{{ trim(str_replace(':count', '', $unreadWord)) }}</span>@endif
                        @if (! empty($item['href']))</a>@else</div>@endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="nx-activity-dropdown-empty">{{ NabuXUI::icon('check-circle') }}{{ $emptyText ?? $t('You’re all caught up', 'چیز تازه‌ای نمانده', 'لا جديد لديك') }}</p>
        @endif
    </div>
</div>
