{{--
    /filament — the Nabu-flavoured dashboard overview, wired in through
    App\Filament\Pages\Dashboard::content(Schema): the aurora hero greeting
    the admin by the hour of day, then four stat cards of live database
    numbers (orders by status, paid revenue, active products, open tasks).
    The cards reuse the package's own .nx-stat classes, the hero reuses the
    <x-nx::backdrop-aurora> block, and the small charts are NabuXUI's
    server-rendered sparklines — the same figure path as the package's
    stat-strip. Every number passes through NabuXUI::formatNumber, so the
    digits follow the panel locale.
--}}
<section class="nx-dash">
    <x-nx::backdrop-aurora class="nx-dash-hero" data-nx-reveal x-nx-reveal>
        <div class="nx-dash-hero-body">
            <p class="nx-dash-hero-hello">{{ $greeting }}</p>
            <p class="nx-dash-hero-text">{{ $tagline }}</p>
        </div>
    </x-nx::backdrop-aurora>

    <div class="nx-dash-stats" data-nx-reveal="group" x-nx-reveal.group aria-label="{{ $statsLabel }}">
        @foreach ($cards as $card)
            <article class="nx-stat" style="--nx-i: {{ $loop->index }}">
                <div class="nx-stat-head">
                    <span class="nx-stat-label">{{ $card['label'] }}</span>
                    <span class="nx-dash-card-icon" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon($card['icon']) }}</span>
                </div>
                <div class="nx-stat-value">{{ \NabuXUI\NabuXUI::formatNumber($card['value'], $card['decimals'] ?? 0) }}</div>
                @if (count($card['trend']) > 1)
                    <x-nx::sparkline
                        :data="$card['trend']"
                        :trend="end($card['trend']) >= $card['trend'][0] ? 'up' : 'down'"
                    />
                @endif
                <p class="nx-stat-caption">{{ $card['caption'] }}</p>
            </article>
        @endforeach
    </div>
</section>

<style>
    /* The overview band: aurora hero card on top, .nx-stat cards under it. */
    .nx-dash { display: grid; gap: var(--nx-space-4); }
    .nx-dash-hero { border-radius: var(--nx-radius-2xl); border: 1px solid var(--nx-border); background: var(--nx-surface); }
    .nx-dash-hero-body { position: relative; display: grid; gap: var(--nx-space-2); justify-items: start; padding: clamp(1.5rem, 3vw, 2.5rem); }
    .nx-dash-hero-hello { margin: 0; font: 700 var(--nx-text-3xl) / 1.3 var(--nx-font-display); letter-spacing: var(--nx-tracking-tighter); color: var(--nx-text); }
    .nx-dash-hero-text { margin: 0; max-inline-size: 42rem; color: var(--nx-text-muted); }
    .nx-dash-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 14rem), 1fr)); gap: var(--nx-space-4); }
    .nx-dash-card-icon { display: grid; place-items: center; color: var(--nx-accent); }
</style>
