{{--
    <x-nx::workspace-shell brand="Nabu" active="inbox" height="36rem" label="Workspace" prompt-placeholder="Ask anything…" :items="[
        ['id' => 'inbox', 'label' => 'Inbox', 'icon' => 'message', 'badge' => 12],
        ['id' => 'agents', 'label' => 'Agents', 'icon' => 'sparkles', 'href' => '/agents', 'navigate' => true],
    ]">
        <x-slot:header>…</x-slot:header>
        … the page …
        <x-slot:footer>…</x-slot:footer>
        <x-slot:prompt><x-nx::prompt wire:submit="ask" wire:model="message" /></x-slot:prompt>
    </x-nx::workspace-shell>

    Items with `href` are links (`navigate` adds wire:navigate); the others are buttons that make
    themselves current and dispatch nx-select with their id. wire:model on the component binds the
    current id (x-modelable). The prompt slot replaces the built-in composer.
--}}
@props(['items' => [], 'active' => null, 'collapsed' => false, 'brand' => null, 'brandMark' => null, 'promptPlaceholder' => null, 'height' => null, 'label' => null])
@php
    use NabuXUI\NabuXUI;
    $lang = substr(app()->getLocale(), 0, 2);
    $words = [
        'fa' => ['collapse' => 'بستن نوار کناری', 'expand' => 'باز کردن نوار کناری'],
        'ar' => ['collapse' => 'طي الشريط الجانبي', 'expand' => 'توسيع الشريط الجانبي'],
    ][$lang] ?? ['collapse' => 'Collapse sidebar', 'expand' => 'Expand sidebar'];
    $active = (string) ($active ?? ($items[0]['id'] ?? ''));
    $sidebar = NabuXUI::id('nx-ws');
    $mark = $brandMark ?? (is_string($brand) && $brand !== '' ? mb_substr($brand, 0, 1) : 'N');
@endphp
<div {{ $attributes->class('nx-workspace')->merge(['style' => $height ? "--nx-workspace-height: {$height}" : null, 'data-collapsed' => $collapsed ? '' : null]) }}
    x-data="nxWorkspaceShell(@js($active), @js((bool) $collapsed))" x-modelable="active" x-bind:data-collapsed="collapsed ? '' : null">
    <div class="nx-workspace-frame">
        <aside id="{{ $sidebar }}" class="nx-workspace-sidebar">
            <div class="nx-workspace-brand">
                <span class="nx-workspace-mark" aria-hidden="true">{{ $mark }}</span>
                @if ($brand)<span class="nx-workspace-brand-text">{{ $brand }}</span>@endif
            </div>
            <nav class="nx-workspace-nav" x-ref="nav" @if ($label) aria-label="{{ $label }}" @endif>
                <span class="nx-indicator" aria-hidden="true"></span>
                <ul>
                    @foreach ($items as $item)
                        @php
                            $itemId = (string) $item['id'];
                            $current = $itemId === $active;
                        @endphp
                        <li>
                            @if (! empty($item['href']))
                                <a href="{{ $item['href'] }}" class="nx-workspace-item" data-value="{{ $itemId }}" data-label="{{ $item['label'] }}" @if (! empty($item['navigate'])) wire:navigate @endif
                                    @if ($current) aria-current="page" @endif x-bind:aria-current="active === @js($itemId) ? 'page' : null" x-on:click="active = @js($itemId)">
                            @else
                                <button type="button" class="nx-workspace-item" data-value="{{ $itemId }}" data-label="{{ $item['label'] }}"
                                    @if ($current) aria-current="page" @endif x-bind:aria-current="active === @js($itemId) ? 'page' : null" x-on:click="select(@js($itemId))">
                            @endif
                                {{ NabuXUI::icon($item['icon'] ?? 'grid') }}
                                <span class="nx-workspace-label">{{ $item['label'] }}</span>
                                @if (isset($item['badge']))<span class="nx-workspace-badge">{{ $item['badge'] }}</span>@endif
                            @if (! empty($item['href']))</a>@else</button>@endif
                        </li>
                    @endforeach
                </ul>
            </nav>
            <div class="nx-workspace-sidebar-foot">
                {{ $footer ?? '' }}
                <button type="button" class="nx-workspace-item nx-workspace-toggle" aria-controls="{{ $sidebar }}" aria-expanded="{{ $collapsed ? 'false' : 'true' }}"
                    data-label="{{ $collapsed ? $words['expand'] : $words['collapse'] }}"
                    x-bind:aria-expanded="collapsed ? 'false' : 'true'" x-bind:data-label="collapsed ? @js($words['expand']) : @js($words['collapse'])" x-on:click="collapsed = ! collapsed">
                    {{ NabuXUI::icon('chevron-left') }}
                    <span class="nx-workspace-label" x-text="collapsed ? @js($words['expand']) : @js($words['collapse'])">{{ $collapsed ? $words['expand'] : $words['collapse'] }}</span>
                </button>
            </div>
        </aside>
        <div class="nx-workspace-main">
            <header class="nx-workspace-header">{{ $header ?? '' }}</header>
            <div class="nx-workspace-content">{{ $slot }}</div>
            @if (isset($prompt) || $promptPlaceholder)
                <div class="nx-workspace-composer">
                    @isset($prompt){{ $prompt }}@else<x-nx::prompt :placeholder="$promptPlaceholder" />@endisset
                </div>
            @endif
        </div>
    </div>
</div>
