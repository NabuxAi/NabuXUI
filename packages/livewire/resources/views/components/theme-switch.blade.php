{{--
    <x-nx::theme-switch :value="'system'" x-on:nx-change="…" />

    Light / system / dark on the core theme store — the same preference the
    header's toggle flips, with a thumb that springs between the three. The
    choice follows changes made elsewhere (another toggle, another tab).
--}}
@props([
    'value' => null,
    'name' => null,
    'label' => null,
    'locale' => null,
])
@php
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Theme', 'پوسته', 'المظهر');
    $names = [
        'light' => $t('Light', 'روشن', 'فاتح'),
        'system' => $t('System', 'سیستم', 'النظام'),
        'dark' => $t('Dark', 'تیره', 'داكن'),
    ];
@endphp
<div {{ $attributes->class('nx-theme-switch') }} role="radiogroup" aria-label="{{ $label }}" x-data="nxThemeSwitch(@js($value))">
    <span class="nx-indicator" aria-hidden="true"></span>
    @foreach ($names as $choice => $text)
        <label class="nx-theme-switch-option" aria-label="{{ $text }}">
            <input class="nx-theme-switch-input" type="radio" name="{{ $name ?? 'nx-theme' }}" value="{{ $choice }}"
                @checked(($value ?? 'system') === $choice) x-on:change="choose(@js($choice))">
            @if ($choice === 'light')
                {{ NabuXUI::icon('sun') }}
            @elseif ($choice === 'dark')
                {{ NabuXUI::icon('moon') }}
            @else
                <span class="nx-theme-switch-half" aria-hidden="true"></span>
            @endif
        </label>
    @endforeach
</div>
