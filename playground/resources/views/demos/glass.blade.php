{{-- Livewire demos: Liquid glass. --}}
<style>
    .pg-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; min-block-size: 20rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .pg-stage[data-tone="night"] { background: var(--nx-ink-950); }
    .pg-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(28px) saturate(1.2); }
    .pg-blobs i { position: absolute; inline-size: 46%; aspect-ratio: 1; border-radius: 50%; opacity: .85; animation: pg-drift 18s ease-in-out infinite alternate; }
    .pg-blobs i:nth-child(1) { inset-block-start: -12%; inset-inline-start: -6%; background: var(--nx-lapis-500); }
    .pg-blobs i:nth-child(2) { inset-block-end: -18%; inset-inline-start: 22%; background: var(--nx-violet-500); animation-delay: -6s; }
    .pg-blobs i:nth-child(3) { inset-block-start: 8%; inset-inline-end: -8%; background: var(--nx-cyan-400); animation-delay: -11s; }
    .pg-blobs i:nth-child(4) { inset-block-end: -6%; inset-inline-end: 12%; inline-size: 28%; background: var(--nx-gold-400); animation-delay: -3s; }
    @keyframes pg-drift { to { translate: calc(9% * var(--nx-motion)) calc(-7% * var(--nx-motion)); scale: calc(1 + .12 * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .pg-blobs i { animation: none; } }
    .pg-words { position: absolute; inset-inline: 0; inset-block-start: 50%; translate: 0 -50%; z-index: -1; margin: 0; text-align: center; font: 800 clamp(2.5rem, 7vw, 5.5rem) / 1.05 var(--nx-font-display); color: color-mix(in oklab, var(--nx-ink-50) 88%, transparent); pointer-events: none; }
    .pg-phone { position: relative; block-size: 26rem; inline-size: min(100%, 20rem); margin-inline: auto; border-radius: 2.5rem; overflow: clip; background: var(--nx-bg); box-shadow: 0 0 0 8px var(--nx-ink-900), var(--nx-shadow-lg); }
    .pg-phone-scroll { position: absolute; inset: 0; overflow-y: auto; overscroll-behavior: contain; }
    .pg-feed { display: grid; gap: .75rem; padding: 1rem 1rem 7rem; }
    .pg-post { display: grid; align-items: end; block-size: 9rem; padding: 1rem; border-radius: var(--nx-radius-xl); color: var(--nx-ink-50); font-weight: 700; background: linear-gradient(135deg, var(--nx-lapis-500), var(--nx-violet-500)); }
    .pg-phone-bar { position: absolute; inset-inline: 0; inset-block-end: 1rem; display: grid; justify-items: center; }
</style>

<div class="pg-grid">
    <section class="pg-stage" style="grid-column: 1 / -1">
        <div class="pg-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
        <p class="pg-words" aria-hidden="true">Liquid · 液体 · سائل · Flüssig · líquido</p>
        <x-nx::glass-panel iridescent follow-light style="display: grid; gap: .75rem; inline-size: min(100%, 30rem)">
            <x-nx::badge tone="accent" dot>Live material</x-nx::badge>
            <h2 class="pg-title">Clarity over blur · 透明</h2>
            <p style="margin: 0; color: var(--nx-text-muted)">The backdrop stays visible. Only the optical edge, the tint and the contrast define this layer.</p>
            <div class="pg-row">
                <x-nx::glass-button icon="sparkles" shimmer wire:click="ping('¡Listo! Glass shimmer sent')">Continue</x-nx::glass-button>
                <x-nx::glass-button icon="heart" iridescent>Save · 保存</x-nx::glass-button>
                <x-nx::glass-button icon="settings" preset="thick" aria-label="Settings" />
            </div>
        </x-nx::glass-panel>
    </section>

    <section class="pg-stage">
        <div class="pg-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
        <div style="display: grid; justify-items: center; gap: 1rem">
            <x-nx::glass-segmented label="Period" wire:model.live="state.period"
                :options="['day' => 'Day', 'week' => 'Semana', 'month' => '月', 'year' => 'سنة']" />
            <x-nx::badge>Livewire: {{ $state['period'] ?? '—' }}</x-nx::badge>
        </div>
    </section>

    <section class="pg-stage" data-tone="night">
        <div class="pg-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
        <x-nx::glass-dock label="Apps" :items="[
            ['label' => 'Files · ファイル', 'icon' => 'folder', 'tone' => 'lapis', 'current' => true],
            ['label' => 'Mail · Correo', 'icon' => 'mail', 'tone' => 'cyan', 'click' => 'ping(\'Mail\')'],
            ['label' => 'Music · موسیقی', 'icon' => 'music', 'tone' => 'rose'],
            ['label' => 'Photos · 사진', 'icon' => 'image', 'tone' => 'gold'],
            ['label' => 'Chat · चैट', 'icon' => 'message', 'tone' => 'green'],
            ['label' => 'Nabu AI', 'icon' => 'sparkles', 'tone' => 'violet'],
        ]" />
    </section>

    <section class="pg-stage">
        <div class="pg-blobs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
        <x-nx::glass-panel preset="frost" style="display: grid; gap: 1.25rem; inline-size: min(100%, 22rem)">
            <x-nx::glass-switch label="Refraction · 屈折" wire:model.live="state.refraction" />
            <x-nx::glass-switch label="Iridescence · Irisation" wire:model.live="state.iridescence" />
            <div style="display: grid; gap: .5rem; font-size: var(--nx-text-sm); font-weight: 600">
                <span>Volume · Lautstärke</span>
                <x-nx::glass-slider label="Volume" wire:model.live="state.volume" suffix="%" />
            </div>
            <x-nx::badge>Livewire: {{ ($state['refraction'] ?? false) ? 'on' : 'off' }} · {{ $state['volume'] ?? '—' }}%</x-nx::badge>
        </x-nx::glass-panel>
    </section>

    <section class="pg-box" style="padding: 1.25rem">
        <div class="pg-phone">
            <div class="pg-phone-scroll">
                <div class="pg-feed">
                    @foreach (['Kyoto · 京都', 'Lisboa', 'Marrakech · مراكش', 'Reykjavík', 'Tehran · تهران', 'Seoul · 서울', 'Oaxaca', 'Istanbul'] as $city)
                        <div class="pg-post">{{ $city }}</div>
                    @endforeach
                </div>
            </div>
            <div class="pg-phone-bar">
                <x-nx::glass-tab-bar label="Main" wire:model.live="state.tab" minimize-on-scroll=".pg-phone-scroll" :items="[
                    ['value' => 'home', 'label' => 'Home', 'icon' => 'home'],
                    ['value' => 'explore', 'label' => 'Explorar', 'icon' => 'globe'],
                    ['value' => 'saved', 'label' => 'Gespeichert', 'icon' => 'heart'],
                    ['value' => 'me', 'label' => 'من', 'icon' => 'user'],
                ]" />
            </div>
        </div>
    </section>

    <section class="pg-box" style="grid-column: 1 / -1; padding: 0; overflow: clip">
        <x-nx::reading-glass :x="0.62" :y="0.3">
            <div style="display: grid; gap: 1rem; padding: 2rem">
                <h2 class="pg-title">Glass that reads with you · 与你同读的玻璃</h2>
                <p style="margin: 0; max-inline-size: 60ch; font-size: var(--nx-text-lg); color: var(--nx-text-muted)">
                    The lens bends the page under it, and the page stays a page: select this sentence, or press the button while the lens sits on top of it.
                    <span lang="fa" dir="rtl">متن فارسی زیر عدسی هم درست خم می‌شود.</span>
                    <span lang="ja">レンズの下の文字も選択できます。</span>
                </p>
                <div class="pg-row">
                    <x-nx::glass-button size="sm" wire:click="ping('Clicked through the glass')">Click me · クリック</x-nx::glass-button>
                    <x-nx::badge tone="success">Selectable</x-nx::badge>
                    <x-nx::badge tone="info">Clickable</x-nx::badge>
                </div>
            </div>
        </x-nx::reading-glass>
    </section>

    <section class="pg-box" style="grid-column: 1 / -1; padding: 0; overflow: clip">
        <x-nx::liquid-ripple style="display: grid; gap: 1rem; padding: 1.5rem">
            <div class="pg-grid" style="grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr)); gap: .75rem">
                @foreach (['Kenji Sato', 'María López', 'Amara Okafor', 'Omar Haddad', 'Lena Fischer', 'Priya Nair', '김지우', 'Zhang Wei'] as $name)
                    <div style="display: flex; align-items: center; gap: .75rem; padding: .75rem; border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); font-weight: 600">
                        <x-nx::avatar :name="$name" size="sm" />{{ $name }}
                    </div>
                @endforeach
            </div>
            <p style="margin: 0; text-align: center; color: var(--nx-text-muted)">Tap anywhere · Touchez n’importe où</p>
        </x-nx::liquid-ripple>
    </section>
</div>
