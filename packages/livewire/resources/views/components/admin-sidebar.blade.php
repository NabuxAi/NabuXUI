{{--
    <x-nx::admin-sidebar :groups="[
        ['label' => 'مدیریت', 'items' => [
            ['id' => 'dashboard', 'label' => 'داشبورد', 'icon' => 'grid', 'badge' => 3],
            ['id' => 'orders', 'label' => 'سفارش‌ها', 'icon' => 'layers', 'href' => '/admin/orders', 'navigate' => true],
        ]],
        ['label' => 'سیستم', 'items' => [['id' => 'settings', 'label' => 'تنظیمات', 'icon' => 'settings']]],
    ]" active="dashboard" brand="Nabu" label="ناوبری پنل">
        <x-slot:footer>…اختیاری…</x-slot:footer>
    </x-nx::admin-sidebar>

    Grouped nav with a spring highlight under the current item (core `indicator`),
    a brand row and a collapse toggle. Inside <x-nx::admin-shell> pass wired: the
    items then follow the shell's Alpine state (active/collapsed) and buttons
    dispatch nx-select; standalone it is server-state only. `wired` also turns on
    the collapse toggle, which needs the shell's scope.
--}}
@props(['groups' => [], 'active' => null, 'collapsed' => false, 'brand' => null, 'brandMark' => null, 'label' => null, 'wired' => false])
@php
    use NabuXUI\NabuXUI;
    $lang = substr(app()->getLocale(), 0, 2);
    $words = [
        'fa' => ['collapse' => 'بستن نوار کناری', 'expand' => 'باز کردن نوار کناری'],
        'ar' => ['collapse' => 'طي الشريط الجانبي', 'expand' => 'توسيع الشريط الجانبي'],
    ][$lang] ?? ['collapse' => 'Collapse sidebar', 'expand' => 'Expand sidebar'];
    $active = (string) ($active ?? ($groups[0]['items'][0]['id'] ?? ''));
    $mark = $brandMark ?? (is_string($brand) && $brand !== '' ? mb_substr($brand, 0, 1) : 'N');
    $badgeOf = fn ($badge) => is_numeric($badge) ? NabuXUI::formatNumber((float) $badge, 0, $lang) : (string) $badge;
@endphp
<aside {{ $attributes->class('nx-admin-sidebar')->merge(['data-collapsed' => $collapsed && ! $wired ? '' : null]) }}>
    <div class="nx-admin-brand">
        <span class="nx-admin-mark" aria-hidden="true">{{ $mark }}</span>
        @if ($brand)<span class="nx-admin-brand-text">{{ $brand }}</span>@endif
    </div>
    <nav class="nx-admin-nav" @if ($label) aria-label="{{ $label }}" @endif>
        <span class="nx-indicator" aria-hidden="true"></span>
        @foreach ($groups as $group)
            @php $groupId = (string) ($group['id'] ?? $group['label'] ?? $loop->index); @endphp
            <section class="nx-admin-group" @if ($groupId) data-group="{{ $groupId }}" @endif>
                @if (! empty($group['label']))
                    <h3 class="nx-admin-group-label">{{ $group['label'] }}</h3>
                @endif
                <ul>
                    @foreach ($group['items'] as $item)
                        @php
                            $itemId = (string) $item['id'];
                            $current = $itemId === $active;
                            $disabled = ! empty($item['disabled']);
                        @endphp
                        <li>
                            @if (! empty($item['href']))
                                <a href="{{ $item['href'] }}" class="nx-admin-item" data-value="{{ $itemId }}" data-label="{{ $item['label'] }}"
                                    @if (! empty($item['navigate'])) wire:navigate @endif
                                    @if ($disabled) aria-disabled="true" @endif
                                    @if ($current) aria-current="page" @endif
                                    @if ($wired) x-bind:aria-current="active === @js($itemId) ? 'page' : null" x-on:click="active = @js($itemId)" @endif>
                            @else
                                <button type="button" class="nx-admin-item" data-value="{{ $itemId }}" data-label="{{ $item['label'] }}"
                                    @if ($disabled) disabled @endif
                                    @if ($current) aria-current="page" @endif
                                    @if ($wired) x-bind:aria-current="active === @js($itemId) ? 'page' : null" x-on:click="select(@js($itemId))" @endif>
                            @endif
                                {{ NabuXUI::icon($item['icon'] ?? 'grid') }}
                                <span class="nx-admin-label">{{ $item['label'] }}</span>
                                @if (isset($item['badge']))<span class="nx-admin-badge">{{ $badgeOf($item['badge']) }}</span>@endif
                            @if (! empty($item['href']))</a>@else</button>@endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach
    </nav>
    <div class="nx-admin-sidebar-foot">
        {{ $footer ?? '' }}
        @if ($wired)
            <button type="button" class="nx-admin-item nx-admin-toggle" aria-expanded="{{ $collapsed ? 'false' : 'true' }}"
                data-label="{{ $collapsed ? $words['expand'] : $words['collapse'] }}"
                x-bind:aria-expanded="collapsed ? 'false' : 'true'" x-bind:data-label="collapsed ? @js($words['expand']) : @js($words['collapse'])" x-on:click="collapsed = ! collapsed">
                {{ NabuXUI::icon('chevron-left') }}
                <span class="nx-admin-label" x-text="collapsed ? @js($words['expand']) : @js($words['collapse']) }}">{{ $collapsed ? $words['expand'] : $words['collapse'] }}</span>
            </button>
        @endif
    </div>
</aside>
