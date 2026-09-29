{{--
    <x-nx::promo-bar id="launch" badge="NEW" href="/pricing" link-label="See plans"
        x-on:nx-dismiss="…">95% off every template — this week only</x-nx::promo-bar>

    An announcement strip, wedoflow-style: a rotated sticker badge, an arrow
    link and a close button. Dismissing folds it away (grid rows 1fr → 0fr)
    and, by default, the choice is remembered for the session.
--}}
@props([
    'id' => 'promo',
    'badge' => null,
    'href' => null,
    'linkLabel' => null,
    'persist' => true,
    'label' => null,
    'locale' => null,
])
@php
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Announcement', 'اطلاعیه', 'إعلان');
@endphp
<aside {{ $attributes->class('nx-promo-bar') }} role="region" aria-label="{{ $label }}"
    x-data="nxPromoBar(@js($id), @js($persist))" x-bind:data-closing="state === 'closing' ? '' : null"
    x-show="state !== 'gone'" x-cloak x-on:transitionend="end()">
    <div class="nx-promo-bar-inner">
        <p class="nx-promo-bar-text">{{ $slot }}@if ($href)
                <a class="nx-promo-bar-link" href="{{ $href }}">{{ $linkLabel ?? $t('More', 'بیشتر', 'المزيد') }} {{ NabuXUI::icon('arrow-right') }}</a>
            @endif</p>
        @if ($badge !== null && $badge !== false)<span class="nx-promo-bar-badge">{{ $badge }}</span>@endif
    </div>
    <button type="button" class="nx-promo-bar-close" aria-label="{{ __('nabuxui::ui.dismiss') }}" x-on:click="dismiss()">
        {{ NabuXUI::icon('x') }}
    </button>
</aside>
