{{--
    The glass dock as an app switcher over a night wallpaper: the magnifier
    glides to the item under the pointer or focus, the tooltip names it, and
    one item carries a live Livewire action. The second dock links out.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .gdk-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; gap: 1.25rem; min-block-size: 19rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); }
    .gdk-stage[data-tone='night'] { background: var(--nx-ink-950); }
    .gdk-stage[data-tone='day'] { background: var(--nx-bg); }
    .gdk-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(30px) saturate(1.25); }
    .gdk-blobs i { position: absolute; inline-size: 48%; aspect-ratio: 1; border-radius: 50%; opacity: .9; animation: gdk-drift 22s ease-in-out infinite alternate; }
    .gdk-blobs i:nth-child(1) { inset-block-start: -16%; inset-inline-start: -8%; background: var(--nx-lapis-500); }
    .gdk-blobs i:nth-child(2) { inset-block-end: -20%; inset-inline-start: 26%; background: var(--nx-violet-500); animation-delay: -7s; }
    .gdk-blobs i:nth-child(3) { inset-block-start: 10%; inset-inline-end: -10%; background: var(--nx-cyan-400); animation-delay: -14s; }
    @keyframes gdk-drift { to { translate: calc(7% * var(--nx-motion)) calc(-9% * var(--nx-motion)); scale: calc(1 + .14 * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .gdk-blobs i { animation: none; } }
</style>

<section class="gdk-stage" data-tone="night">
    <div class="gdk-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
    <div style="display: grid; gap: 1rem; justify-items: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl); color: var(--nx-ink-50)">{{ $say('Your workspace, one glide away', 'ورک‌اسپیس شما، یک سُرش فاصله') }}</h3>
        <x-nx::glass-dock :label="$say('Workspace apps', 'برنامه‌های ورک‌اسپیس')" :items="[
            ['label' => $say('Files', 'فایل‌ها'), 'icon' => 'folder', 'tone' => 'lapis', 'current' => true],
            ['label' => $say('Mail', 'ایمیل'), 'icon' => 'mail', 'tone' => 'cyan', 'click' => "ping('".$say('3 unread — opening Mail', '۳ ناخوانده — ایمیل باز می‌شود')."')"],
            ['label' => $say('Music', 'موسیقی'), 'icon' => 'music', 'tone' => 'rose'],
            ['label' => $say('Photos', 'عکس‌ها'), 'icon' => 'image', 'tone' => 'gold'],
            ['label' => $say('Chat', 'گفت‌وگو'), 'icon' => 'message', 'tone' => 'green'],
            ['label' => $say('Nabu AI', 'هوش نابو'), 'icon' => 'sparkles', 'tone' => 'violet', 'click' => "save('".$say('The assistant is warming up', 'دستیار دارد گرم می‌شود')."')"],
        ]" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: color-mix(in oklab, var(--nx-ink-100) 78%, transparent)">
            {{ $say('Sweep the pointer across, or Tab through — the magnifier and the name follow.', 'موس را بکشید یا با Tab بروید — ذره‌بین و نام دنبال می‌کنند.') }}
        </p>
    </div>
</section>

<section class="gdk-stage" data-tone="day">
    <div class="gdk-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
    <div style="display: grid; gap: 1rem; justify-items: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg); color: var(--nx-text)">{{ $say('The same dock as links', 'همان داک به‌شکل لینک') }}</h3>
        <x-nx::glass-dock preset="frost" :label="$say('Demo shortcuts', 'میان‌بر دموها')" :items="[
            ['label' => $say('All demos', 'همهٔ دموها'), 'icon' => 'grid', 'tone' => 'lapis', 'href' => '/components', 'current' => true],
            ['label' => 'Glass panel', 'icon' => 'layers', 'tone' => 'violet', 'href' => '/components/glass/glass-panel'],
            ['label' => 'Glass tab bar', 'icon' => 'home', 'tone' => 'cyan', 'href' => '/components/glass/glass-tab-bar'],
            ['label' => 'Admin shell', 'icon' => 'settings', 'tone' => 'gold', 'href' => '/components/menus/admin-shell'],
        ]" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Items with href are real anchors; the rest can carry a Livewire click.', 'آیتم‌های دارای href لنگر واقعی‌اند؛ بقیه می‌توانند کلیک Livewire داشته باشند.') }}
        </p>
    </div>
</section>
