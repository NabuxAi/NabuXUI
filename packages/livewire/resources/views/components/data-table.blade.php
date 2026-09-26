{{--
    <x-nx::data-table caption="Projects" sort="budget:descending" max-height="26rem" :rows="$projects" :columns="[
        ['key' => 'name', 'label' => 'Project', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'format' => 'status', 'sortable' => true],
        ['key' => 'budget', 'label' => 'Budget', 'format' => 'currency:USD', 'sortable' => true],
        ['key' => 'updated', 'label' => 'Updated', 'format' => 'date'],
    ]" />

    format: number · compact · percent (0.42 → 42%) · currency:EUR · date · datetime · status.
    Sorting runs in the browser: the rows are reordered and glide to their new places.
    Server-side instead: sort-action="sortBy" makes each header call $wire.sortBy(key, direction);
    re-render the rows sorted and pass the current sort back (sort="name:ascending"). Rows keep a
    wire:key from row-key (default "id"), so they still glide after the morph.
--}}
@props(['columns' => [], 'rows' => [], 'caption' => null, 'captionHidden' => false, 'sort' => null, 'sortAction' => null, 'rowKey' => 'id', 'maxHeight' => null, 'density' => null, 'emptyText' => null])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    if (is_string($sort) && $sort !== '') {
        [$sortKey, $sortDirection] = array_pad(explode(':', $sort, 2), 2, 'ascending');
        $sort = ['key' => $sortKey, 'direction' => $sortDirection === 'descending' ? 'descending' : 'ascending'];
    }
    $sort = is_array($sort) && isset($sort['key']) ? $sort : null;
    $numeric = fn (?string $format) => (bool) preg_match('/^(number|compact|percent|currency)/', (string) $format);
    $ranks = ['failed' => 0, 'running' => 1, 'queued' => 2, 'success' => 3, 'canceled' => 4];
    $intl = class_exists(\NumberFormatter::class);

    $format = function ($value, ?string $format) use ($locale, $intl) {
        if ($value === null || $value === '') {
            return '—';
        }
        [$kind, $arg] = array_pad(explode(':', (string) $format, 2), 2, null);
        switch ($kind) {
            case 'number':
                return NabuXUI::formatNumber((float) $value, floor((float) $value) == (float) $value ? 0 : 2, $locale);
            case 'compact':
                $n = (float) $value;
                foreach ([1e9 => 'B', 1e6 => 'M', 1e3 => 'K'] as $step => $suffix) {
                    if (abs($n) >= $step) {
                        return NabuXUI::formatNumber(round($n / $step, 1), fmod(round($n / $step, 1), 1.0) == 0.0 ? 0 : 1, $locale).$suffix;
                    }
                }
                return NabuXUI::formatNumber($n, 0, $locale);
            case 'percent':
                $p = (float) $value * 100;
                return NabuXUI::formatNumber($p, floor($p) == $p ? 0 : 1, $locale).'%';
            case 'currency':
                $code = strtoupper($arg ?: 'USD');
                return $intl ? (string) (new \NumberFormatter($locale, \NumberFormatter::CURRENCY))->formatCurrency((float) $value, $code) : NabuXUI::formatNumber((float) $value, 2, $locale).' '.$code;
            case 'date':
            case 'datetime':
                try {
                    $date = $value instanceof \DateTimeInterface ? $value : new \DateTimeImmutable((string) $value, new \DateTimeZone('UTC'));
                } catch (\Exception) {
                    return (string) $value;
                }
                if (class_exists(\IntlDateFormatter::class)) {
                    return (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::MEDIUM, $kind === 'datetime' ? \IntlDateFormatter::SHORT : \IntlDateFormatter::NONE, 'UTC'))->format($date);
                }
                return $date->format($kind === 'datetime' ? 'M j, Y H:i' : 'M j, Y');
            default:
                return (string) $value;
        }
    };

    $sortValue = function ($value, ?string $format) use ($ranks) {
        if ($format === 'status') {
            return $ranks[(string) $value] ?? 5;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->getTimestamp();
        }
        return is_bool($value) ? (int) $value : (string) $value;
    };

    $alignOf = fn (array $column) => $column['align'] ?? ($numeric($column['format'] ?? null) ? 'end' : 'start');
    $tableId = NabuXUI::id('nx-table');
@endphp
<div {{ $attributes->class('nx-data-table')->merge([
    'data-nx-reveal' => '',
    'data-density' => $density === 'compact' ? 'compact' : null,
    'style' => $maxHeight ? "--nx-table-max: {$maxHeight}" : null,
    'aria-labelledby' => $caption ? "{$tableId}-caption" : null,
]) }} x-data="nxDataTable(@js(['sort' => $sort, 'action' => $sortAction, 'locale' => $locale]))">
    <table>
        @if ($caption)<caption id="{{ $tableId }}-caption" @class(['nx-visually-hidden' => $captionHidden])>{{ $caption }}</caption>@endif
        <thead>
            <tr>
                @foreach ($columns as $column)
                    @php
                        $align = $alignOf($column);
                        $direction = $sort && $sort['key'] === $column['key'] ? $sort['direction'] : null;
                    @endphp
                    <th scope="col" data-key="{{ $column['key'] }}" @if ($align !== 'start') data-align="{{ $align }}" @endif @if ($direction) aria-sort="{{ $direction }}" @endif @if (! empty($column['width'])) style="inline-size: {{ $column['width'] }}" @endif>
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
            </tr>
        </thead>
        <tbody x-ref="body">
            @forelse (array_values($rows) as $index => $row)
                @php $key = (string) (data_get($row, $rowKey) ?? $index); @endphp
                <tr data-key="{{ $key }}" wire:key="{{ $tableId }}-{{ $key }}" style="--nx-i: {{ $index }}">
                    @foreach ($columns as $column)
                        @php
                            $value = data_get($row, $column['key']);
                            $columnFormat = $column['format'] ?? null;
                            $align = $alignOf($column);
                        @endphp
                        <td data-sort="{{ $sortValue($value, $columnFormat) }}" @if ($align !== 'start') data-align="{{ $align }}" @endif @if ($numeric($columnFormat)) data-numeric @endif>
                            @if ($columnFormat === 'status')
                                <x-nx::status-badge :status="(string) $value" size="sm" />
                            @else
                                {{ $format($value, $columnFormat) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr><td class="nx-data-table-empty" colspan="{{ max(1, count($columns)) }}">{{ $emptyText ?? __('nabuxui::ui.noResults') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
