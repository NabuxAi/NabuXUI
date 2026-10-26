{{-- data: the at-a-glance strip — three stat cards and two progress meters. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="display: grid; gap: 1rem">
    <div class="aist-recipe-stats">
        <x-nx::stat-card :label="$say('Revenue', 'درآمد')" :value="4820" :delta="12.4" :trend="[31, 38, 35, 44, 41, 48]" :prefix="'€'" />
        <x-nx::stat-card :label="$say('Orders', 'سفارش‌ها')" :value="1284" :delta="8" :trend="[12, 15, 11, 18, 16, 21]" />
        <x-nx::stat-card :label="$say('Churn', 'ریزش')" :value="2.1" :decimals="1" :delta="-0.5" :invertDelta="true" :trend="[31, 38, 35, 44, 41, 48]" :suffix="'%'" />
    </div>
    <div class="aist-recipe-grid">
        <div style="display: grid; gap: .5rem">
            <div class="aist-recipe-row" style="justify-content: space-between; font-size: var(--nx-text-sm)">
                <b>{{ $say('Storage', 'فضا') }}</b><span style="color: var(--nx-text-muted)">۲٫۴ / ۱۰ گیگابایت</span>
            </div>
            <x-nx::progress :value="24" />
        </div>
        <div style="display: grid; gap: .5rem">
            <div class="aist-recipe-row" style="justify-content: space-between; font-size: var(--nx-text-sm)">
                <b>{{ $say('Weekly goal', 'هدف هفته') }}</b><span style="color: var(--nx-text-muted)">۷۸٪</span>
            </div>
            <x-nx::progress :value="78" tone="success" />
        </div>
    </div>
</div>
