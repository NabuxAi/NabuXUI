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

    {{-- The live band: the six newest orders (each row links into the orders
         resource) beside the open-tasks board, whose ticks call the page's
         toggleTask() through the todo block's toggle-action. Deliberately no
         data-nx-reveal here — these re-render on every Livewire tick. --}}
    <div class="nx-dash-widgets">
        <section class="nx-dash-widget" aria-labelledby="nx-dash-orders-title">
            <header class="nx-dash-widget-head">
                <h2 class="nx-dash-widget-title" id="nx-dash-orders-title">{{ $recentOrdersLabel }}</h2>
                <a class="nx-dash-widget-link" href="{{ \App\Filament\Resources\Orders\OrderResource::getUrl('index') }}">{{ $allOrdersLabel }}</a>
            </header>
            <p class="nx-dash-widget-caption">{{ $recentOrdersCaption }}</p>
            <div class="nx-data-table nx-dash-orders" data-density="compact">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">شماره</th>
                            <th scope="col">مشتری</th>
                            <th scope="col">وضعیت</th>
                            <th scope="col" data-align="end">مبلغ کل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr wire:key="dash-order-{{ $order['id'] }}">
                                <td><a class="nx-dash-order-link" href="{{ $order['url'] }}">{{ $order['number'] }}</a></td>
                                <td>{{ $order['customer'] }}</td>
                                <td><x-nx::status-badge :status="$order['badge']" :label="$order['statusLabel']" size="sm" /></td>
                                <td data-align="end" data-numeric>{{ \NabuXUI\NabuXUI::formatNumber($order['total'], 0) }}</td>
                            </tr>
                        @empty
                            <tr><td class="nx-dash-empty" colspan="4">هنوز سفارشی ثبت نشده است.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="nx-dash-widget" aria-labelledby="nx-dash-tasks-title">
            <header class="nx-dash-widget-head">
                <h2 class="nx-dash-widget-title" id="nx-dash-tasks-title">{{ $tasksLabel }}</h2>
            </header>
            <p class="nx-dash-widget-caption">{{ $tasksCaption }}</p>
            <div class="nx-dash-tasks">
                <x-nx::todo
                    :groups="$taskGroups"
                    :quick-add="false"
                    toggle-action="toggleTask"
                    label="{{ $tasksLabel }}"
                />
            </div>
        </section>
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

    /* The live widgets: orders table on one side, the task board on the other. */
    .nx-dash-widgets { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 22rem), 1fr)); gap: var(--nx-space-4); align-items: start; }
    .nx-dash-widget { display: grid; gap: var(--nx-space-2); padding: var(--nx-space-4); border: 1px solid var(--nx-border); border-radius: var(--nx-radius-2xl); background: var(--nx-surface); box-shadow: var(--nx-shadow-sm), inset 0 1px 0 var(--nx-highlight); }
    .nx-dash-widget-head { display: flex; align-items: baseline; justify-content: space-between; gap: var(--nx-space-3); }
    .nx-dash-widget-title { margin: 0; font: 600 var(--nx-text-lg) / 1.3 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); color: var(--nx-text); }
    .nx-dash-widget-caption { margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-muted); }
    .nx-dash-widget-link { font-size: var(--nx-text-sm); font-weight: 500; color: var(--nx-accent); text-decoration: none; }
    .nx-dash-widget-link:hover { text-decoration: underline; }
    .nx-dash-widget-link:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; border-radius: var(--nx-radius-sm); }
    .nx-dash-order-link { color: var(--nx-accent); font-weight: 500; text-decoration: none; }
    .nx-dash-order-link:hover { text-decoration: underline; }
    .nx-dash-order-link:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; border-radius: var(--nx-radius-sm); }
    .nx-dash-orders { max-block-size: 26rem; }
    .nx-dash-tasks { max-block-size: 26rem; overflow: auto; overscroll-behavior: contain; scrollbar-width: thin; padding-inline-end: 0.25rem; }
    /* The dashboard board is tick-only: no quick-add, no remove, no grip. */
    .nx-dash-tasks .nx-todo-add, .nx-dash-tasks .nx-todo-remove, .nx-dash-tasks .nx-todo-grip { display: none; }
    .nx-dash-empty { color: var(--nx-text-muted); }
</style>
