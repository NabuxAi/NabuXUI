{{--
    The glass tab bar in its natural home: a phone frame with a scrolling
    travel feed. The bar shrinks as the feed scrolls (minimize-on-scroll with
    a container selector), the active tab rides under the lens, and the pick
    syncs to Livewire. A compact, href-linked bar follows.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $tab = $state['tripTab'] ?? 'home';
    $tabNames = [
        'home' => $say('Home', 'خانه'),
        'explore' => $say('Explore', 'کاوش'),
        'saved' => $say('Saved', 'ذخیره‌ها'),
        'me' => $say('Me', 'من'),
    ];
@endphp
<style>
    .gtb-phone { position: relative; block-size: 26rem; inline-size: min(100%, 20rem); margin-inline: auto; border-radius: 2.5rem; overflow: clip; background: var(--nx-bg); box-shadow: 0 0 0 8px var(--nx-ink-900), var(--nx-shadow-lg); }
    .gtb-feed { position: absolute; inset: 0; overflow-y: auto; overscroll-behavior: contain; }
    .gtb-items { display: grid; gap: .75rem; padding: 1rem 1rem 7rem; }
    .gtb-post { display: grid; align-items: end; block-size: 8.5rem; padding: 1rem; border-radius: var(--nx-radius-xl); color: var(--nx-ink-50); font-weight: 700; background: linear-gradient(135deg, var(--nx-lapis-500), var(--nx-violet-500)); }
    .gtb-post:nth-child(2n) { background: linear-gradient(135deg, var(--nx-violet-500), var(--nx-cyan-400)); }
    .gtb-post:nth-child(3n) { background: linear-gradient(135deg, var(--nx-gold-400), var(--nx-lapis-500)); }
    .gtb-bar { position: absolute; inset-inline: 0; inset-block-end: 1rem; display: grid; justify-items: center; }
    .gtb-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; gap: 1rem; min-block-size: 14rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gtb-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(26px) saturate(1.2); }
    .gtb-blobs i { position: absolute; inline-size: 44%; aspect-ratio: 1; border-radius: 50%; opacity: .82; animation: gtb-drift 19s ease-in-out infinite alternate; }
    .gtb-blobs i:nth-child(1) { inset-block-start: -12%; inset-inline-start: -6%; background: var(--nx-lapis-500); }
    .gtb-blobs i:nth-child(2) { inset-block-end: -14%; inset-inline-end: -8%; background: var(--nx-gold-400); animation-delay: -6s; }
    @keyframes gtb-drift { to { translate: calc(9% * var(--nx-motion)) calc(-6% * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .gtb-blobs i { animation: none; } }
</style>

<section class="pg-box" style="gap: 1rem; padding: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A travel app’s bottom bar', 'نوار پایین اپ سفر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Scroll the feed — the bar shrinks itself out of the way; the active tab sits under the lens and the pick lands in Livewire.', 'در فید اسکرول کنید — نوار خودش کوچک می‌شود؛ تب فعال زیر عدسی می‌نشیند و انتخاب به Livewire می‌رود.') }}
        </p>
    </div>
    <div class="gtb-phone">
        <div class="gtb-feed">
            <div class="gtb-items">
                @foreach ([
                    $say('Kyoto', 'کیوتو'),
                    $say('Lisboa', 'لیسبون'),
                    $say('Marrakech', 'مراکش'),
                    $say('Reykjavík', 'ریکیاویک'),
                    $say('Istanbul', 'استانبول'),
                    $say('Seoul', 'سئول'),
                    $say('Istanbul', 'استانبول'),
                ] as $city)
                    <div class="gtb-post">{{ $city }}</div>
                @endforeach
            </div>
        </div>
        <div class="gtb-bar">
            <x-nx::glass-tab-bar :label="$say('Main', 'اصلی')" :value="$tab" wire:model.live="state.tripTab" minimize-on-scroll=".gtb-feed" :items="[
                ['value' => 'home', 'label' => $tabNames['home'], 'icon' => 'home'],
                ['value' => 'explore', 'label' => $tabNames['explore'], 'icon' => 'globe'],
                ['value' => 'saved', 'label' => $tabNames['saved'], 'icon' => 'heart'],
                ['value' => 'me', 'label' => $tabNames['me'], 'icon' => 'user'],
            ]" />
        </div>
    </div>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Active tab on the server:', 'تب فعال روی سرور:') }} <code>{{ $tabNames[$tab] ?? $tab }}</code>
    </p>
</section>

<section class="gtb-stage">
    <div class="gtb-blobs" aria-hidden="true"><i></i><i></i></div>
    <div style="display: grid; gap: .875rem; justify-items: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg); color: var(--nx-text)">{{ $say('Compact, and made of links', 'فشرده و ساخته‌شده از لینک') }}</h3>
        <x-nx::glass-tab-bar compact preset="frost" :label="$say('Demo shortcuts', 'میان‌بر دموها')" :items="[
            ['value' => 'catalog', 'label' => $say('All demos', 'همهٔ دموها'), 'icon' => 'grid', 'href' => '/components'],
            ['value' => 'glass', 'label' => $say('Glass', 'شیشه'), 'icon' => 'layers', 'href' => '/components/glass/glass-panel'],
            ['value' => 'menus', 'label' => $say('Menus', 'منو'), 'icon' => 'menu', 'href' => '/components/menus/fold-menu'],
        ]" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Items with href become real anchors.', 'آیتم‌های دارای href لنگر واقعی می‌شوند.') }}
        </p>
    </div>
</section>
