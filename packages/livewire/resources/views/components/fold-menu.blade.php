{{--
    <x-nx::fold-menu label="Menu" :sections="[
        ['title' => 'Explore', 'links' => [
            ['label' => 'Home', 'href' => '/', 'icon' => 'home', 'current' => true],
            ['label' => 'Pricing', 'href' => '/pricing', 'description' => 'Plans for every team'],
        ]],
        ['title' => 'Say hi', 'links' => [['label' => 'Ping the team', 'icon' => 'message', 'click' => '$wire.ping(\'Hi\')']]],
    ]">
        <x-slot:footer>…an extra last fold (a call to action, a language switch)…</x-slot:footer>
    </x-nx::fold-menu>

    One fold per section: three or four read best. Links take `href` (add
    `navigate` for wire:navigate) or `click`, an Alpine expression.
--}}
@props(['sections' => [], 'label' => null, 'align' => 'start', 'navigate' => false, 'navLabel' => null])
@php
    $id = \NabuXUI\NabuXUI::id('nx-fold');
    $label ??= __('nabuxui::ui.menu');
    $hasFooter = isset($footer) && trim((string) $footer) !== '';
    $folds = array_values($sections);
    if ($hasFooter) {
        $folds[] = ['footer' => true];
    }
    $count = count($folds);
@endphp
<div {{ $attributes->class('nx-fold-menu') }} x-data="nxFoldMenu(@js($align === 'end' ? 'end' : 'start'))">
    <button type="button" class="nx-fold-menu-trigger" x-ref="trigger" popovertarget="{{ $id }}" aria-controls="{{ $id }}"
        aria-expanded="false" x-bind:aria-expanded="open ? 'true' : 'false'" x-on:keydown="keyTrigger($event)">
        <span>{{ $label }}</span>
        <span class="nx-fold-menu-burger" aria-hidden="true"><i></i><i></i></span>
    </button>
    <nav class="nx-fold-menu-panel" id="{{ $id }}" x-ref="panel" popover="auto" wire:ignore.self aria-label="{{ $navLabel ?? $label }}" style="--_n: {{ $count }}" x-on:keydown="keyPanel($event)">
        {{-- Each fold hangs from the bottom edge of the one before it: they nest. --}}
        @foreach ($folds as $i => $fold)
            <div class="nx-fold-menu-fold" style="--nx-i: {{ $i }}" @if ($i === 0) data-first @endif @if ($i === $count - 1) data-last @endif>
                <div class="nx-fold-menu-face">
                    @if (! empty($fold['footer']))
                        {{ $footer }}
                    @else
                        @if (! empty($fold['title']))<p class="nx-fold-menu-title">{{ $fold['title'] }}</p>@endif
                        <ul class="nx-fold-menu-links">
                            @foreach ($fold['links'] ?? [] as $j => $link)
                                <li style="--nx-j: {{ $j }}">
                                    @if (! empty($link['href']))
                                        <a class="nx-fold-menu-link" href="{{ $link['href'] }}" @if (! empty($link['current'])) aria-current="page" @endif @if ($navigate) wire:navigate @endif x-on:click="close()">
                                    @else
                                        <button type="button" class="nx-fold-menu-link" x-on:click="{{ trim(($link['click'] ?? '').'; close(true)', '; ') }}">
                                    @endif
                                        @if (! empty($link['icon']))<span class="nx-fold-menu-icon">{{ \NabuXUI\NabuXUI::icon($link['icon']) }}</span>@endif
                                        <span class="nx-fold-menu-text"><span>{{ $link['label'] }}</span>@if (! empty($link['description']))<span class="nx-fold-menu-description">{{ $link['description'] }}</span>@endif</span>
                                    @if (! empty($link['href']))</a>@else</button>@endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
        @endforeach
        @foreach ($folds as $fold)
            </div>
        @endforeach
    </nav>
</div>
