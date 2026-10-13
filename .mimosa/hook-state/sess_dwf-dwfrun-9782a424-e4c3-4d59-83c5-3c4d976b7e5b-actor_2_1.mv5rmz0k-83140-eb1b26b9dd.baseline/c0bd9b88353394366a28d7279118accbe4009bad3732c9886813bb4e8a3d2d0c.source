{{--
    <x-nx::usage-card title="Storage" plan="Pro" :limit="10" unit="GB" :decimals="1" note="Resets on the 1st"
        :categories="['Documents' => 3.2, 'Voice notes' => 2.1, 'Images' => 1.4, 'Embeddings' => 0.7]"
        :action="['label' => 'Upgrade plan', 'href' => '/billing']" />

    categories: ['Label' => value] or a list of ['label', 'value']; `used` defaults to their sum.
    The bar fills segment by segment; past 75% the percentage turns warning, past 90% danger.
    An <x-slot:actions> replaces the button.
--}}
@props(['title' => null, 'plan' => null, 'limit' => 100, 'used' => null, 'categories' => [], 'unit' => null, 'decimals' => 0, 'note' => null, 'action' => null])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = [
        'fa' => ['usedOf' => '{used} از {limit} مصرف شده', 'upgrade' => 'ارتقای طرح'],
        'ar' => ['usedOf' => 'تم استخدام {used} من {limit}', 'upgrade' => 'ترقية الخطة'],
    ][$lang] ?? ['usedOf' => '{used} of {limit} used', 'upgrade' => 'Upgrade plan'];
    $list = [];
    foreach ($categories as $key => $category) $list[] = is_array($category) ? ['label' => (string) $category['label'], 'value' => (float) $category['value']] : ['label' => (string) $key, 'value' => (float) $category];
    $used ??= array_sum(array_map(fn ($c) => max(0, $c['value']), $list));
    $limit = (float) $limit;
    $ratio = $limit > 0 ? $used / $limit : 0;
    $level = $ratio >= 0.9 ? 'danger' : ($ratio >= 0.75 ? 'warning' : null);
    $amount = fn ($v) => NabuXUI::formatNumber($v, (int) $decimals, $locale).($unit ? ' '.$unit : '');
    $percent = fn ($r) => NabuXUI::formatNumber(round(min($r, 9.99) * 100), 0, $locale).'%';
@endphp
<article {{ $attributes->class('nx-usage-card')->merge(['data-nx-reveal' => '']) }} x-data x-nx-reveal>
    @if ($title || $plan)
        <header class="nx-usage-card-head">
            @if ($title)<p class="nx-usage-card-title">{{ $title }}</p>@endif
            @if ($plan)<x-nx::badge tone="accent">{{ $plan }}</x-nx::badge>@endif
        </header>
    @endif
    <p class="nx-usage-card-count">
        <span>
            @foreach (preg_split('/(\{used\}|\{limit\})/', $words['usedOf'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if ($part === '{used}')<x-nx::number :value="$used" :decimals="(int) $decimals" />{{ $unit ? ' '.$unit : '' }}@elseif ($part === '{limit}'){{ $amount($limit) }}@else{{ $part }}@endif
            @endforeach
        </span>
        <span class="nx-usage-card-percent" @if ($level) data-level="{{ $level }}" @endif>{{ $percent($ratio) }}</span>
    </p>
    <div class="nx-usage-card-bar" aria-hidden="true">
        @foreach ($list as $i => $category)
            <span class="nx-usage-card-segment" style="--nx-share: {{ $limit > 0 ? round(max(0, $category['value']) / $limit, 4) : 0 }}; --nx-series: var(--nx-chart-{{ min($i, 6) + 1 }}); --nx-i: {{ $i }}"></span>
        @endforeach
        @if ($ratio < 1)<span class="nx-usage-card-free" style="--nx-share: {{ round(1 - $ratio, 4) }}"></span>@endif
    </div>
    <ul class="nx-usage-card-legend">
        @foreach ($list as $i => $category)
            <li class="nx-usage-card-row" style="--nx-i: {{ $i }}">
                <span class="nx-usage-card-swatch" style="--nx-series: var(--nx-chart-{{ min($i, 6) + 1 }})" aria-hidden="true"></span>
                <span class="nx-usage-card-label">{{ $category['label'] }}</span>
                <span class="nx-usage-card-value">{{ $amount($category['value']) }}</span>
                <span class="nx-usage-card-share">{{ $percent($limit > 0 ? $category['value'] / $limit : 0) }}</span>
            </li>
        @endforeach
    </ul>
    @if ($note || $action || isset($actions))
        <footer class="nx-usage-card-foot">
            @if ($note)<p class="nx-usage-card-note" @if ($level) data-level="{{ $level }}" @endif>{{ $note }}</p>@endif
            @if (isset($actions))
                {{ $actions }}
            @elseif ($action)
                <x-nx::button size="sm" :variant="$level ? 'primary' : 'secondary'" :effect="$level ? 'shine' : null" :icon="$action['icon'] ?? 'zap'" :href="$action['href'] ?? null">{{ $action['label'] ?? $words['upgrade'] }}</x-nx::button>
            @endif
        </footer>
    @endif
</article>
