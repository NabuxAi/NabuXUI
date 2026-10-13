{{--
    /admin/orders — the order desk: the data-table block's markup grown a
    status-badge column (dot badge, one tone per status) and a track button.
    Sorting is server-side (the headers call sortBy) so the rows keep their
    wire:key and glide through the morph. Each row's button opens the
    order-tracking dialog — the shipment's steps, the event log stitched from
    what already happened, and the facts (items, recipient, placed, ETA) in
    Jalali dates for fa. Copy comes from admin.orders_* keys.
--}}
<x-admin.page active="orders" :title="__('admin.orders_title')" :subtitle="__('admin.orders_subtitle')">
    <style>
        /* Orders-page pieces the shared blocks don't carry (tokens only, both themes). */
        .op-count { font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
        .op-number { font-family: var(--nx-font-mono); font-size: var(--nx-text-sm); color: var(--nx-text); }
        .op-items { color: var(--nx-text-muted); }
        .op-total-cur { font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
        .op-track { inline-size: 1%; white-space: nowrap; text-align: end; }
        .op-facts { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 11rem), 1fr)); gap: var(--nx-space-3); margin: 0 0 var(--nx-space-4); padding: var(--nx-space-3) var(--nx-space-4); border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2); }
        .op-fact { display: grid; gap: 0.15rem; }
        .op-fact dt { font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
        .op-fact dd { margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text); font-weight: 600; }
    </style>
    <div class="ap-grid">
        <section class="ap-box">
            <div class="ap-row" style="justify-content: space-between">
                <x-nx::input :label="__('admin.orders_search_label')"
                    :placeholder="__('admin.orders_search_placeholder')" icon="search"
                    wire:model.live.debounce.300ms="search" style="max-inline-size: 20rem" />
                <div class="ap-row">
                    <span class="op-count">{{ $countText }}</span>
                    <x-nx::chip-filter :options="$chips" :counts="$counts" :label="__('admin.orders_filter_status')" wire:model.live="status" />
                </div>
            </div>

            <div class="nx-data-table op-table" data-nx-reveal style="--nx-table-max: 26rem"
                x-data="nxDataTable(@js(['sort' => $sort, 'action' => 'sortBy', 'locale' => $locale]))">
                <table>
                    <caption class="nx-visually-hidden">{{ __('admin.orders_title') }}</caption>
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                                @php
                                    $align = $column['align'] ?? 'start';
                                    $direction = $sort['key'] === $column['key'] ? $sort['direction'] : null;
                                @endphp
                                <th scope="col" data-key="{{ $column['key'] }}" @if ($align !== 'start') data-align="{{ $align }}" @endif @if ($direction) aria-sort="{{ $direction }}" @endif>
                                    @if (! empty($column['sortable']))
                                        <button type="button" class="nx-data-table-sort" @if ($direction) data-direction="{{ $direction }}" @endif x-on:click="press(@js($column['key']))">
                                            <span>{{ $column['label'] }}</span>
                                            <svg class="nx-data-table-sort-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6"/></svg>
                                        </button>
                                    @else
                                        {{ $column['label'] }}
                                    @endif
                                </th>
                            @endforeach
                            <th scope="col" class="op-track"><span class="nx-visually-hidden">{{ __('admin.orders_track') }}</span></th>
                        </tr>
                    </thead>
                    <tbody x-ref="body">
                        @forelse ($rows as $row)
                            <tr data-key="{{ $row['id'] }}" wire:key="o{{ $row['id'] }}" style="--nx-i: {{ $row['index'] }}">
                                <td data-sort="{{ $row['numberSort'] }}"><span class="op-number">{{ $row['number'] }}</span></td>
                                <td data-sort="{{ mb_strtolower($row['customer']) }}">{{ $row['customer'] }}</td>
                                <td data-sort="{{ $row['itemsCount'] }}"><span class="op-items">{{ $row['items'] }}</span></td>
                                <td data-sort="{{ $row['totalSort'] }}" data-align="end" data-numeric>
                                    {{ $row['total'] }} <span class="op-total-cur">{{ __('admin.invoice_currency') }}</span>
                                </td>
                                <td data-sort="{{ $row['statusSort'] }}">
                                    <x-nx::badge :tone="$row['statusTone']" data-size="sm" dot>{{ $row['status'] }}</x-nx::badge>
                                </td>
                                <td data-sort="{{ $row['dateSort'] }}">{{ $row['date'] }}</td>
                                <td class="op-track">
                                    <x-nx::button size="sm" variant="ghost" icon="zap" wire:click="track('{{ $row['id'] }}')">{{ __('admin.orders_track') }}</x-nx::button>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="nx-data-table-empty" colspan="{{ count($columns) + 1 }}">{{ __('nabuxui::ui.noResults') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @if ($track)
        <x-nx::dialog wire:model="trackingOpen" :title="$track['title']" size="lg">
            <dl class="op-facts">
                <div class="op-fact">
                    <dt>{{ $track['itemsLabel'] }}</dt>
                    <dd>{{ $track['itemsText'] }}</dd>
                </div>
                <div class="op-fact">
                    <dt>{{ __('admin.orders_track_recipient') }}</dt>
                    <dd>{{ $track['recipient'] }}</dd>
                </div>
                <div class="op-fact">
                    <dt>{{ __('admin.orders_track_placed') }}</dt>
                    <dd>{{ $track['placed'] }}</dd>
                </div>
                @if ($track['eta'])
                    <div class="op-fact">
                        <dt>{{ __('admin.orders_track_eta') }}</dt>
                        <dd>{{ $track['eta'] }}</dd>
                    </div>
                @endif
            </dl>

            <x-nx::order-tracking :number="$track['number']" :status="$track['badge']" :status-label="$track['statusLabel']"
                :steps="$track['steps']" :events="$track['events']" />
            <x-slot:footer>
                <div class="ap-row" style="justify-content: flex-end">
                    <x-nx::button variant="ghost" wire:click="$set('trackingOpen', false)">{{ __('admin.orders_close') }}</x-nx::button>
                </div>
            </x-slot:footer>
        </x-nx::dialog>
    @endif
</x-admin.page>
