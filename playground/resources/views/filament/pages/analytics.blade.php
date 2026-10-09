{{--
    تحلیل‌ها — the body of App\Filament\Pages\Analytics::content(Schema):
    the paid-revenue total card with its server-rendered sparkline on top,
    then the order-status donut beside the last-seven-days sales bars.
    The charts are the package's own <x-nx::donut-chart>, <x-nx::bar-chart>
    and <x-nx::sparkline> — pure CSS/SVG, they reveal themselves on scroll
    (x-nx-reveal) and dispatch no events, so nothing on this page needs
    wiring back to Livewire. Every figure the page class writes itself went
    through NabuXUI::formatNumber; the charts format their own values in
    the panel locale.
--}}
<section class="nx-analytics" data-nx-reveal="group" x-nx-reveal.group aria-label="{{ $totalCard['label'] }}">
    <article class="nx-stat nx-analytics-total" style="--nx-i: 0">
        <div class="nx-stat-head">
            <span class="nx-stat-label">{{ $totalCard['label'] }}</span>
        </div>
        <div class="nx-stat-value">{{ $totalCard['value'] }}</div>
        @if (count($totalCard['trend']) > 1)
            <x-nx::sparkline
                :data="$totalCard['trend']"
                :trend="$totalCard['trendUp'] ? 'up' : 'down'"
            />
        @endif
        <p class="nx-stat-caption">{{ $totalCard['caption'] }}</p>
    </article>

    <div class="nx-analytics-grid">
        <article class="nx-analytics-panel" style="--nx-i: 1">
            <x-nx::donut-chart
                :title="$donut['title']"
                :subtitle="$donut['subtitle']"
                :data="$donut['data']"
                :center-label="$donut['centerLabel']"
            />
        </article>
        <article class="nx-analytics-panel" style="--nx-i: 2">
            <x-nx::bar-chart
                :title="$bars['title']"
                :subtitle="$bars['subtitle']"
                :data="$bars['data']"
            />
        </article>
    </div>
</section>

<style>
    /* The analytics band: the total card up top, the two charts side by side. */
    .nx-analytics { display: grid; gap: var(--nx-space-4); }
    .nx-analytics-total {
        padding: var(--nx-space-5);
        border: 1px solid var(--nx-border);
        border-radius: var(--nx-radius-2xl);
        background: var(--nx-surface);
    }
    .nx-analytics-total .nx-sparkline { inline-size: 100%; block-size: auto; }
    .nx-analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 22rem), 1fr));
        gap: var(--nx-space-4);
    }
    .nx-analytics-panel {
        display: grid;
        gap: var(--nx-space-4);
        align-content: start;
        min-inline-size: 0;
        padding: var(--nx-space-5);
        border: 1px solid var(--nx-border);
        border-radius: var(--nx-radius-2xl);
        background: var(--nx-surface);
    }
</style>
