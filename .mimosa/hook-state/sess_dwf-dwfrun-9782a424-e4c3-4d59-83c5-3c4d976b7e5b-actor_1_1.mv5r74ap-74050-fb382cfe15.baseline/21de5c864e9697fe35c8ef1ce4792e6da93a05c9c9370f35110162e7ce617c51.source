{{--
    Glass switches in a preferences panel over a blob field: three real
    notification settings bound to Livewire, and the states a switch can be
    born with — checked and disabled.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $news = (bool) ($state['news'] ?? true);
    $alerts = (bool) ($state['alerts'] ?? false);
    $focus = (bool) ($state['focus'] ?? false);
@endphp
<style>
    .gsw-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; gap: 1.25rem; min-block-size: 20rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gsw-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(28px) saturate(1.2); }
    .gsw-blobs i { position: absolute; inline-size: 46%; aspect-ratio: 1; border-radius: 50%; opacity: .85; animation: gsw-drift 21s ease-in-out infinite alternate; }
    .gsw-blobs i:nth-child(1) { inset-block-start: -14%; inset-inline-start: 8%; background: var(--nx-cyan-400); }
    .gsw-blobs i:nth-child(2) { inset-block-end: -16%; inset-inline-end: -6%; background: var(--nx-lapis-500); animation-delay: -8s; }
    .gsw-blobs i:nth-child(3) { inset-block-start: 16%; inset-inline-end: 20%; inline-size: 26%; background: var(--nx-gold-400); animation-delay: -13s; }
    @keyframes gsw-drift { to { translate: calc(-8% * var(--nx-motion)) calc(7% * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .gsw-blobs i { animation: none; } }
</style>

<section class="gsw-stage">
    <div class="gsw-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
    <x-nx::glass-panel preset="frost" style="display: grid; gap: 1rem; inline-size: min(100%, 23rem)">
        <h3 class="pg-title" style="margin: 0; font-size: var(--nx-text-xl)">{{ $say('Quiet hours', 'ساعات سکوت') }}</h3>
        <x-nx::glass-switch :label="$say('Weekly digest', 'خبرنامهٔ هفتگی')" wire:model.live="state.news" />
        <x-nx::glass-switch :label="$say('Mention alerts', 'هشدار ذکر شدن')" wire:model.live="state.alerts" />
        <x-nx::glass-switch :label="$say('Focus mode (mutes everything)', 'حالت تمرکز (همه را ساکت می‌کند)')" wire:model.live="state.focus" />
        <div class="pg-row" style="gap: .5rem">
            @if ($focus)
                <x-nx::badge tone="accent" pulse>{{ $say('Focus on — delivery paused', 'تمرکز روشن — ارسال متوقف') }}</x-nx::badge>
            @else
                <x-nx::badge>{{ $say($news ? 'Digest on' : 'Digest off', $news ? 'خبرنامه روشن' : 'خبرنامه خاموش').($alerts ? ' · '.($fa ? 'هشدارها روشن' : 'Alerts on') : '') }}</x-nx::badge>
            @endif
            <x-nx::glass-button size="sm" icon="check" wire:click="save(@js($say('Preferences saved', 'ترجیحات ذخیره شد')))">{{ $say('Save', 'ذخیره') }}</x-nx::glass-button>
        </div>
    </x-nx::glass-panel>
</section>

<section class="gsw-stage" style="min-block-size: 13rem">
    <div class="gsw-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
    <div style="display: grid; gap: .875rem; justify-items: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg); color: var(--nx-text)">{{ $say('Born checked, or locked', 'با تیک به‌دنیا، یا قفل') }}</h3>
        <div class="pg-row" style="gap: 2rem">
            <x-nx::glass-switch :label="$say('On by default', 'پیش‌فرض روشن')" checked />
            <x-nx::glass-switch :label="$say('Locked by policy', 'با سیاست قفل شده')" checked disabled />
        </div>
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Held-down knobs clear to glass — press and hold one.', 'دکمه‌های نگه‌داشته‌شده به شیشه می‌گرایند — یکی را نگه دارید.') }}
        </p>
    </div>
</section>
